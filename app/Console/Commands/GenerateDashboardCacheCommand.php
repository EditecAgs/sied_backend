<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateDashboardCacheJob;

class GenerateDashboardCacheCommand extends Command
{
    protected $signature = 'dashboard:generate-cache';
    protected $description = 'Genera manualmente el cache del dashboard';

    public function handle()
    {
        GenerateDashboardCacheJob::dispatch();

        $this->info('Job GenerateDashboardCacheJob enviado a la cola.');

        return Command::SUCCESS;
    }
}

