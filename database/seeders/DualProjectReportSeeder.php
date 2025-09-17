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
                'dual_type_id' => 1,
                'name' => 'Proyecto de Prueba',
                'id_dual_area' => 1,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => false,
                'qualification' => null,
                'advisor' => 'interno',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 2],
            [
                'dual_project_id' => 2,
                'dual_type_id' => 2,
                'name' => 'Proyecto de Prueba2',
                'id_dual_area' => 4,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => false,
                'qualification' => null,
                'advisor' => 'interno',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 3],
            [
                'dual_project_id' => 3,
                'dual_type_id' => 3,
                'name' => 'Proyecto de Prueba3',
                'id_dual_area' => 1,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => true,
                'is_hired' => true,
                'qualification' => 8,
                'advisor' => 'interno',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 4],
            [
                'dual_project_id' => 4,
                'dual_type_id' => 4,
                'name' => 'Proyecto de Prueba4',
                'id_dual_area' => 10,
                'period_start' => now()->subMonths(4),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => true,
                'is_hired' => false,
                'qualification' => 7,
                'advisor' => 'externo',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 5],
            [
                'dual_project_id' => 5,
                'dual_type_id' => 1,
                'name' => 'Proyecto de Prueba5',
                'id_dual_area' => 15,
                'period_start' => now()->subMonths(4),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => true,
                'qualification' => 9,
                'advisor' => 'externo',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 6],
            [
                'dual_project_id' => 6,
                'dual_type_id' => 2,
                'name' => 'Proyecto de Prueba6',
                'id_dual_area' => 9,
                'period_start' => now()->subMonths(6),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => true,
                'is_hired' => true,
                'qualification' => 10,
                'advisor' => 'interno',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 7],
            [
                'dual_project_id' => 7,
                'dual_type_id' => 3,
                'name' => 'Proyecto de Prueba7',
                'id_dual_area' => 10,
                'period_start' => now()->subMonths(5),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => false,
                'qualification' => null,
                'advisor' => 'externo',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 8],
            [
                'dual_project_id' => 8,
                'dual_type_id' => 4,
                'name' => 'Proyecto de Prueba8',
                'id_dual_area' => 7,
                'period_start' => now()->subMonths(3),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => true,
                'is_hired' => false,
                'qualification' => 6,
                'advisor' => 'interno',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 9],
            [
                'dual_project_id' => 9,
                'dual_type_id' => 1,
                'name' => 'Proyecto de Prueba9',
                'id_dual_area' => 3,
                'period_start' => now()->subMonths(2),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => true,
                'qualification' => 8,
                'advisor' => 'externo',
            ]
        );

        DualProjectReport::updateOrCreate(
            ['id' => 10],
            [
                'dual_project_id' => 10,
                'dual_type_id' => 2,
                'name' => 'Proyecto de Prueba10',
                'id_dual_area' => 5,
                'period_start' => now()->subMonths(1),
                'period_end' => now()->addMonths(6),
                'status_document' => 1,
                'economic_support' => 1,
                'amount' => 1000,
                'is_concluded' => true,
                'is_hired' => true,
                'qualification' => 9,
                'advisor' => 'interno',
            ]
        );
    }
}
