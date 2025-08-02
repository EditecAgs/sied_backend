<?php

namespace Database\Seeders;

use App\Models\DualProjectReport;
use Illuminate\Database\Seeder;

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
        DualProjectReport::updateOrCreate(
            ['id' => 2],
            [
                'dual_project_id' => 2,
                'name' => 'Proyecto de Prueba2',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 4,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 3],
            [
                'dual_project_id' => 3,
                'name' => 'Proyecto de Prueba3',
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
        DualProjectReport::updateOrCreate(
            ['id' => 4],
            [
                'dual_project_id' => 4,
                'name' => 'Proyecto de Prueba4',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 10,
                'period_start' => now()->subMonths(4),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 5],
            [
                'dual_project_id' => 5,
                'name' => 'Proyecto de Prueba5',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 15,
                'period_start' => now()->subMonths(4),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 6],
            [
                'dual_project_id' => 6,
                'name' => 'Proyecto de Prueba6',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 9,
                'period_start' => now()->subMonths(6),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 7],
            [
                'dual_project_id' => 7,
                'name' => 'Proyecto de Prueba7',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 10,
                'period_start' => now()->subMonths(5),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 8],
            [
                'dual_project_id' => 8,
                'name' => 'Proyecto de Prueba8',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 7,
                'period_start' => now()->subMonths(3),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 9],
            [
                'dual_project_id' => 9,
                'name' => 'Proyecto de Prueba9',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 3,
                'period_start' => now()->subMonths(2),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
        DualProjectReport::updateOrCreate(
            ['id' => 10],
            [
                'dual_project_id' => 10,
                'name' => 'Proyecto de Prueba10',
                'number_men' => 1,
                'number_women' => 1,
                'id_dual_area' => 5,
                'period_start' => now()->subMonths(1),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
            ]
        );
    }
}
