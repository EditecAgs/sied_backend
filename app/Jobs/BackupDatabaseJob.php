<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 3600;

    public function __construct(
        protected ?string $backupDisk = null,
        protected bool $keepOnlyLastBackup = true,
        protected int $maxBackupsToKeep = 1
    ) {
        $this->backupDisk = $backupDisk ?? 'local';
    }

    public function handle(): void
    {
        try {
            Log::info('Iniciando BackupDatabaseJob - MySQL', [
                'keep_only_last' => $this->keepOnlyLastBackup,
                'max_backups' => $this->maxBackupsToKeep
            ]);

            $lockKey = 'database_backup_in_progress';
            if (Cache::has($lockKey)) {
                Log::warning('Ya hay un backup en progreso');
                return;
            }

            Cache::put($lockKey, now(), now()->addHours(2));

            $this->ensureBackupDirectoryExists();

            $existingBackups = $this->getExistingBackups();
            Log::info('Backups existentes antes del nuevo backup', [
                'count' => count($existingBackups),
                'backups' => $existingBackups
            ]);

            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $databaseName = config('database.connections.mysql.database');
            $filename = "backup_{$databaseName}_{$timestamp}.sql.gz"; // Cambiado a .sql.gz
            $backupPath = "backups/{$filename}";

            Log::info('Generando nuevo backup', [
                'filename' => $filename,
                'disk' => $this->backupDisk
            ]);

            $tempDir = Storage::disk('local')->path('temp_backups');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempFile = $tempDir . '/' . str_replace('.gz', '', $filename);

            $this->backupMySQL($tempFile);

            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                throw new \Exception('El archivo de backup está vacío');
            }

            Log::info('Backup SQL generado', [
                'size' => $this->formatBytes(filesize($tempFile))
            ]);

            $compressedFile = $this->compressBackup($tempFile);

            if (!file_exists($compressedFile) || filesize($compressedFile) === 0) {
                throw new \Exception('Error al comprimir el backup');
            }

            Log::info('Backup comprimido', [
                'compressed_size' => $this->formatBytes(filesize($compressedFile))
            ]);

            Storage::disk($this->backupDisk)->put($backupPath, file_get_contents($compressedFile));

            if (!Storage::disk($this->backupDisk)->exists($backupPath)) {
                throw new \Exception('El nuevo backup no se pudo guardar correctamente');
            }

            $newBackupSize = Storage::disk($this->backupDisk)->size($backupPath);

            Log::info('Nuevo backup guardado exitosamente', [
                'backup_path' => $backupPath,
                'size' => $this->formatBytes($newBackupSize)
            ]);

            if ($this->keepOnlyLastBackup) {
                $this->deleteAllBackupsExcept($backupPath);
            } elseif ($this->maxBackupsToKeep > 0) {
                $this->keepOnlyNBackups($this->maxBackupsToKeep);
            }

            $this->cleanupTempFiles($tempFile, $compressedFile);

            Log::info('BackupDatabaseJob completado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error en BackupDatabaseJob: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } finally {
            Cache::forget('database_backup_in_progress');
        }
    }

    /**
     * Asegurar que el directorio de backups existe
     */
    protected function ensureBackupDirectoryExists(): void
    {
        try {
            $disk = Storage::disk($this->backupDisk);
            if (!$disk->exists('backups')) {
                $disk->makeDirectory('backups');
                Log::info('Directorio de backups creado');
            }
        } catch (\Exception $e) {
            Log::warning('Error al crear directorio de backups: ' . $e->getMessage());
        }
    }

    /**
     * Obtener todos los backups existentes
     */
    protected function getExistingBackups(): array
    {
        try {
            $disk = Storage::disk($this->backupDisk);

            if (!$disk->exists('backups')) {
                return [];
            }

            $backups = collect($disk->files('backups'))
                ->filter(fn($file) => str_ends_with($file, '.sql.gz'))
                ->sortByDesc(fn($file) => $disk->lastModified($file))
                ->values()
                ->toArray();

            return $backups;

        } catch (\Exception $e) {
            Log::warning('Error al obtener backups: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Eliminar todos los backups excepto el especificado
     */
    protected function deleteAllBackupsExcept(string $keepPath): void
    {
        try {
            $disk = Storage::disk($this->backupDisk);

            if (!$disk->exists('backups')) {
                return;
            }

            $backups = collect($disk->files('backups'))
                ->filter(fn($file) => str_ends_with($file, '.sql.gz'));

            $deletedCount = 0;
            foreach ($backups as $backup) {
                if ($backup !== $keepPath) {
                    $disk->delete($backup);
                    $deletedCount++;
                    Log::info('Backup eliminado', ['backup' => $backup]);
                }
            }

            Log::info('Limpieza completada', [
                'kept' => $keepPath,
                'deleted' => $deletedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar backups: ' . $e->getMessage());
        }
    }

    /**
     * Mantener solo los N backups más recientes
     */
    protected function keepOnlyNBackups(int $keepCount): void
    {
        try {
            $backups = $this->getExistingBackups();

            if (count($backups) > $keepCount) {
                $toDelete = array_slice($backups, $keepCount);
                $disk = Storage::disk($this->backupDisk);

                foreach ($toDelete as $backup) {
                    $disk->delete($backup);
                    Log::info('Backup antiguo eliminado', ['backup' => $backup]);
                }

                Log::info('Limpieza completada', [
                    'kept' => $keepCount,
                    'deleted' => count($toDelete)
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Error limpiando backups: ' . $e->getMessage());
        }
    }

    protected function backupMySQL(string $tempFile): void
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $escapedPassword = str_replace(['\\', '"', '$'], ['\\\\', '\"', '\$'], $password);

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password="%s" %s ' .
            '--single-transaction --routines --triggers --events --hex-blob ' .
            '--default-character-set=utf8mb4 > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $escapedPassword,
            escapeshellarg($database),
            escapeshellarg($tempFile)
        );

        Log::info('Ejecutando mysqldump');
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $errorMessage = implode("\n", $output);
            throw new \Exception('Error en mysqldump: ' . $errorMessage);
        }

        if (filesize($tempFile) === 0) {
            throw new \Exception('mysqldump generó archivo vacío');
        }
    }

    protected function compressBackup(string $filePath): string
    {
        $compressedPath = $filePath . '.gz';

        $command = sprintf('gzip -c %s > %s 2>&1', escapeshellarg($filePath), escapeshellarg($compressedPath));
        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($compressedPath) || filesize($compressedPath) === 0) {
            Log::warning('Gzip falló, usando compresión PHP nativa');

            $source = fopen($filePath, 'rb');
            $destination = gzopen($compressedPath, 'wb9');

            if (!$source || !$destination) {
                throw new \Exception('Error al abrir archivos para compresión');
            }

            while (!feof($source)) {
                gzwrite($destination, fread($source, 8192));
            }

            fclose($source);
            gzclose($destination);
        }

        if (!file_exists($compressedPath) || filesize($compressedPath) === 0) {
            throw new \Exception('Error: No se pudo comprimir el archivo');
        }

        return $compressedPath;
    }

    protected function cleanupTempFiles(string ...$files): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function failed(\Throwable $exception): void
    {
        Cache::forget('database_backup_in_progress');
        Log::error('BackupDatabaseJob falló: ' . $exception->getMessage());
    }
}
