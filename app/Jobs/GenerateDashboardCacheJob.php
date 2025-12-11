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
        
        Cache::put("dashboard_cache", $cache, now()->addMinutes(2));
    }
}
