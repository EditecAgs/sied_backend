<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\GenerateDashboardCacheJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new GenerateDashboardCacheJob)
    ->name('Dashboard Cache Update')
    ->description('Actualiza job todos los días a la medianoche')
    ->dailyAt('00:00'); 

// Schedule::job(new GenerateDashboardCacheJob)
//     ->name('Dashboard Cache Update')
//     ->description('Actualiza cache del dashboard cada 2 minutos')
//     ->everyTwoMinutes();


// Opción B: Cada hora
// Schedule::job(new GenerateDashboardCacheJob)
//     ->name('Dashboard Cache Update')
//     ->hourly();

// Opción C: Con condiciones 
// Schedule::job(new GenerateDashboardCacheJob)
//     ->name('Dashboard Cache Update')
//     ->everyFiveMinutes()
//     ->between('08:00', '18:00') // Solo de 8AM a 6PM
//     ->weekdays(); // Solo días laborables