<?php

namespace Database\Seeders;

use App\Models\DualProject;
use Illuminate\Database\Seeder;

class DualProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualProject::updateOrCreate(
            ['id' => 1],
            [
                'has_report' => 1,
                'id_institution' => 1,
                'number_student' => 2, 
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 2],
            [
                'has_report' => 1,
                'id_institution' => 1,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 3],
            [
                'has_report' => 1,
                'id_institution' => 1,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 4],
            [
                'has_report' => 1,
                'id_institution' => 1,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 5],
            [
                'has_report' => 1,
                'id_institution' => 1,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 6],
            [
                'has_report' => 1,
                'id_institution' => 2,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 7],
            [
                'has_report' => 1,
                'id_institution' => 2,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 8],
            [
                'has_report' => 1,
                'id_institution' => 3,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 9],
            [
                'has_report' => 1,
                'id_institution' => 4,
                'number_student' => 0,
            ]
        );
        DualProject::updateOrCreate(
            ['id' => 10],
            [
                'has_report' => 1,
                'id_institution' => 5,
                'number_student' => 0,
            ]
        );
    }
}
