<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DualProjectReport;

class DualProjectReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualProjectReport::updateOrCreate(
            ['id' => 1],
            [
                'dual_project_id' => 1,
                'name' => 'Proyecto de Prueba',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 1,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
    }
}
