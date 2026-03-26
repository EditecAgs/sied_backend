<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\BackupDatabaseJob;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:database
                            {--disk=local : Disco de almacenamiento}
                            {--keep-only-last : Mantener solo el último backup}
                            {--max=1 : Número máximo de backups a mantener}';

    protected $description = 'Realiza un backup de la base de datos MySQL (solo mantiene el último backup exitoso)';

    public function handle()
    {
        $disk = $this->option('disk');
        $keepOnlyLast = $this->option('keep-only-last') !== false;
        $max = (int) $this->option('max');

        $this->info('Iniciando backup de base de datos...');
        $this->newLine();

        $this->line('Configuración:');
        $this->line("   • Disco: {$disk}");
        $this->line("   • Mantener solo último backup: " . ($keepOnlyLast ? 'Sí' : 'No'));
        $this->line("   • Máximo de backups: {$max}");
        $this->newLine();

        try {
            $this->showCurrentBackup();

            dispatch(new BackupDatabaseJob($disk, $keepOnlyLast, $max));

            $this->info('Job de backup despachado exitosamente.');
            $this->info('Puedes ver el progreso en: storage/logs/laravel.log');

            $this->info('Procesando backup... (esto puede tomar unos segundos)');
            sleep(3);

            $this->newLine();
            $this->showCurrentBackup();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function showCurrentBackup(): void
    {
        try {
            $disk = Storage::disk($this->option('disk') ?? 'local');

            if ($disk->exists('backups')) {
                $backups = collect($disk->files('backups'))
                    ->filter(fn($file) => str_ends_with($file, '.sql.gz'))
                    ->sortByDesc(fn($file) => $disk->lastModified($file))
                    ->values();

                if ($backups->isNotEmpty()) {
                    $this->line('Backups existentes:');
                    foreach ($backups as $index => $backup) {
                        $size = $this->formatBytes($disk->size($backup));
                        $modified = \Carbon\Carbon::createFromTimestamp($disk->lastModified($backup));
                        $this->line("   " . ($index + 1) . ". {$backup}");
                        $this->line("      Tamaño: {$size} | Fecha: {$modified->format('Y-m-d H:i:s')}");
                    }
                } else {
                    $this->line('No hay backups existentes');
                }
            } else {
                $this->line('No hay backups existentes');
            }
            $this->newLine();
        } catch (\Exception $e) {
            $this->line("No se pudo verificar backups existentes");
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
}
