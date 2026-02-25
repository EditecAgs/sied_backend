<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use App\Models\Institution;
use App\Models\State;
use App\Services\DashboardService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class GenerateDashboardCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Iniciando GenerateDashboardCacheJob');

            $service = new DashboardService();

            $cache = [
                "global" => $service->getAllMetrics(null, null),
                "institutions" => [],
                "states" => []
            ];

            foreach (Institution::all() as $inst) {
                $cache["institutions"][$inst->id] =
                    $service->getAllMetrics(null, $inst->id);
            }

            foreach (State::all() as $state) {
                $cache["states"][$state->id] =
                    $service->getAllMetrics($state->id, null);
            }

            Cache::put("dashboard_cache", $cache, now()->addDay());
            Cache::put("dashboard_cache_generated_at", now(), now()->addDay());

            Log::info('GenerateDashboardCacheJob completado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error en GenerateDashboardCacheJob: ' . $e->getMessage());
            throw $e;
        } finally {
            Cache::forget('dashboard_cache_refreshing');
            Cache::forget('dashboard_cache_refreshing_at');
            Log::info('Locks liberados del dashboard_cache');
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Cache::forget('dashboard_cache_refreshing');
        Cache::forget('dashboard_cache_refreshing_at');

        Log::error('GenerateDashboardCacheJob falló: ' . $exception->getMessage());
    }
}
