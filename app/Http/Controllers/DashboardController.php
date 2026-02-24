<?php /** @noinspection PhpIllegalArrayKeyTypeInspection */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\RefreshDashboardCacheRequest;
use App\Jobs\GenerateDashboardCacheJob;
use App\Http\Resources\DashboardCacheRefreshResource;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private function getCache()
    {
        return Cache::get('dashboard_cache');
    }

    private function fetchFromCache($metricKey)
    {
        $idState = request('id_state');
        $idInstitution = request('id_institution');

        $cache = $this->getCache();
        if (!$cache) {
            return response()->json(['error' => 'Cache not ready'], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        if (!empty($idInstitution)) {
            return response()->json($cache['institutions'][$idInstitution][$metricKey] ?? [], Response::HTTP_OK);
        }

        if (!empty($idState)) {
            return response()->json($cache['states'][$idState][$metricKey] ?? [], Response::HTTP_OK);
        }

        return response()->json($cache['global'][$metricKey] ?? [], Response::HTTP_OK);
    }

    public function countDualProjectCompleted()
    {
        return $this->fetchFromCache('countDualProjectCompleted');
    }

    public function countRegisteredStudents()
    {
        return $this->fetchFromCache('countRegisteredStudents');
    }

    public function countRegisteredOrganizations()
    {
        return $this->fetchFromCache('countRegisteredOrganizations');
    }

    public function countOrganizationsByScope()
    {
        return $this->fetchFromCache('countOrganizationsByScope');
    }

    public function countProjectsByMonth()
    {
        return $this->fetchFromCache('countProjectsByMonth');
    }

    public function countProjectsByArea()
    {
        return $this->fetchFromCache('countProjectsByArea');
    }

    public function countProjectsBySector()
    {
        return $this->fetchFromCache('countProjectsBySector');
    }

    public function countProjectsBySectorPlanMexico()
    {
        return $this->fetchFromCache('countProjectsBySectorPlanMexico');
    }

    public function countProjectsByEconomicSupport()
    {
        return $this->fetchFromCache('countProjectsByEconomicSupport');
    }

    public function averageAmountByEconomicSupport()
    {
        return $this->fetchFromCache('averageAmountByEconomicSupport');
    }

    public function getInstitutionProjectPercentage()
    {
        return $this->fetchFromCache('getInstitutionProjectPercentage');
    }

    public function countProjectsByDualType()
    {
        return $this->fetchFromCache('countProjectsByDualType');
    }

    public function countOrganizationsByCluster()
    {
        return $this->fetchFromCache('countOrganizationsByCluster');
    }

    public function countProjectsByCluster()
    {
        return $this->fetchFromCache('countProjectsByCluster');
    }

    public function statsByBenefitType()
    {
        return $this->fetchFromCache('statsByBenefitType');
    }
    public function countProjectsByDocumentStatus()
    {
        return $this->fetchFromCache('countProjectsByDocumentStatus');
    }
    public function countProjectsByStatus()
    {
        return $this->fetchFromCache('countProjectsByStatus');
    }
    public function getMicroCredentialsStats()
    {
        return $this->fetchFromCache('getMicroCredentialsStats');
    }
    public function getCertificationsStats()
    {
        return $this->fetchFromCache('getCertificationsStats');
    }
    public function getDiplomasStats()
    {
        return $this->fetchFromCache('getDiplomasStats');
    }
    public function getDualAreasStats()
    {
        return $this->fetchFromCache('getDualAreasStats');
    }
    public function showFullCache()
    {
        $cache = $this->getCache();

        if (!$cache) {
            return response()->json([
                'error' => 'Cache not ready',
                'timestamp' => now()->toDateTimeString()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $institutionKeys = array_keys($cache['institutions'] ?? []);
        $stateKeys = array_keys($cache['states'] ?? []);

        $sampleInstitutionKeys = array_slice($institutionKeys, 0, 5);
        $sampleStateKeys = array_slice($stateKeys, 0, 5);

        $availableMetrics = isset($cache['global']) ? array_keys($cache['global']) : [];

        return response()->json([
            'timestamp' => now()->toDateTimeString(),
            'cache_ttl' => '2 minutes',
            'structure' => [
                'has_global' => !empty($cache['global']),
                'institutions_count' => count($institutionKeys),
                'states_count' => count($stateKeys),
                'sample_institution_keys' => $sampleInstitutionKeys,
                'sample_state_keys' => $sampleStateKeys,
                'available_metrics' => $availableMetrics
            ],
            'cache' => $cache
        ]);
    }

    public function refreshCache(RefreshDashboardCacheRequest $request)
    {
        try {
            // Verificar si alguien ya está refrescando la caché
            if (Cache::has('dashboard_cache_refreshing') && !$request->boolean('force')) {
                return response()->json([
                    'success' => false,
                    'message' => 'La caché ya está siendo actualizada. Por favor espera.',
                    'data' => [
                        'locked_by' => Cache::get('dashboard_cache_refreshing'),
                        'locked_at' => Cache::get('dashboard_cache_refreshing_at'),
                        'force_option' => 'Puedes usar ?force=true para forzar la actualización'
                    ]
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            // Si se forza, liberar el lock anterior
            if ($request->boolean('force')) {
                Cache::forget('dashboard_cache_refreshing');
                Cache::forget('dashboard_cache_refreshing_at');
            }

            // Bloquear para evitar múltiples refrescos simultáneos
            Cache::put('dashboard_cache_refreshing', auth()->user()->email ?? 'system', now()->addMinutes(5));
            Cache::put('dashboard_cache_refreshing_at', now()->toIso8601String());

            // Si es síncrono (útil para debugging)
            if ($request->boolean('sync')) {
                return $this->refreshCacheSync();
            }

            // Asíncrono (recomendado para producción)
            return $this->refreshCacheAsync();

        } catch (\Exception $e) {
            // Liberar el lock en caso de error
            Cache::forget('dashboard_cache_refreshing');
            Cache::forget('dashboard_cache_refreshing_at');

            Log::error('Error al refrescar caché del dashboard: ' . $e->getMessage(), [
                'exception' => $e,
                'user' => auth()->user()->email ?? 'system'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al refrescar la caché: ' . $e->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresca la caché de forma síncrona (útil para debugging)
     */
    private function refreshCacheSync()
    {
        $start = microtime(true);

        $job = new GenerateDashboardCacheJob();
        $job->handle();

        $executionTime = round(microtime(true) - $start, 2);

        return response()->json(
            new DashboardCacheRefreshResource([
                'success' => true,
                'message' => 'Caché actualizada correctamente (síncrono)',
                'execution_time' => $executionTime,
                'cache_stats' => $this->getCacheStats(),
            ]),
            Response::HTTP_OK
        );
    }

    /**
     * Refresca la caché de forma asíncrona (recomendado)
     */
    private function refreshCacheAsync()
    {
        // Dispatch el job
        $jobId = Bus::dispatch(new GenerateDashboardCacheJob());

        // Obtener estadísticas de la caché actual (la vieja)
        $oldCacheStats = $this->getCacheStats();

        return response()->json(
            new DashboardCacheRefreshResource([
                'success' => true,
                'message' => 'Actualización de caché iniciada. Los cambios se verán en breve.',
                'job_id' => $jobId,
                'cache_stats' => $oldCacheStats,
            ]),
            Response::HTTP_ACCEPTED // 202 Accepted
        );
    }

    /**
     * Obtiene estadísticas de la caché actual
     */
    private function getCacheStats(): array
    {
        $cache = Cache::get('dashboard_cache');

        return [
            'exists' => !is_null($cache),
            'age' => $this->getCacheAge(),
            'institutions_count' => count($cache['institutions'] ?? []),
            'states_count' => count($cache['states'] ?? []),
            'metrics_count' => count($cache['global'] ?? []),
        ];
    }

    /**
     * Calcula la edad de la caché
     */
    private function getCacheAge(): ?string
    {
        $cacheTime = Cache::get('dashboard_cache_generated_at');

        if (!$cacheTime) {
            return null;
        }

        $minutes = now()->diffInMinutes($cacheTime);

        if ($minutes < 60) {
            return "{$minutes} minutos";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours < 24) {
            return "{$hours} horas, {$remainingMinutes} minutos";
        }

        $days = floor($hours / 24);
        $remainingHours = $hours % 24;

        return "{$days} días, {$remainingHours} horas";
    }

    /**
     * Verifica el estado de la caché
     */
    public function checkCacheStatus()
    {
        $cache = Cache::get('dashboard_cache');
        $isRefreshing = Cache::has('dashboard_cache_refreshing');

        return response()->json([
            'success' => true,
            'data' => [
                'cache_exists' => !is_null($cache),
                'is_refreshing' => $isRefreshing,
                'refreshing_by' => $isRefreshing ? Cache::get('dashboard_cache_refreshing') : null,
                'refreshing_since' => $isRefreshing ? Cache::get('dashboard_cache_refreshing_at') : null,
                'stats' => $this->getCacheStats(),
                'generated_at' => Cache::get('dashboard_cache_generated_at'),
            ]
        ]);
    }

}
