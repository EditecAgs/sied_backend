<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardCacheRefreshResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->resource['success'],
            'message' => $this->resource['message'],
            'data' => [
                'execution_time' => $this->resource['execution_time'] ?? null,
                'job_id' => $this->resource['job_id'] ?? null,
                'cache_stats' => $this->resource['cache_stats'] ?? [
                        'exists' => false,
                        'institutions_count' => 0,
                        'states_count' => 0,
                        'metrics_count' => 0,
                    ],
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'api_version' => '1.0.0',
                'cache_ttl' => '24 hours',
            ],
        ];
    }
}
