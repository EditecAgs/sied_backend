<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\GenerateDashboardCacheJob;
use App\Jobs\BackupDatabaseJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new GenerateDashboardCacheJob)
    ->name('Dashboard Cache Update')
    ->description('Actualiza job todos los días a la medianoche')
    ->dailyAt('00:00');


Schedule::job(new BackupDatabaseJob())
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/backup-schedule.log'));
