<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialty::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Manufactura',
                'id_institution' => 1,
                'id_career' => 1,
            ],
        );
        Specialty::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Derecho Penal',
                'id_institution' => 2,
                'id_career' => 13,
            ],
        );
        Specialty::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Aeronáutica',
                'id_institution' => 3,
                'id_career' => 14,
            ],
        );
        Specialty::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Diseño de semiconductores',
                'id_institution' => 4,
                'id_career' => 15,
            ],
        );
        Specialty::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Antropología',
                'id_institution' => 5,
                'id_career' => 16,
            ],
        );
    }
}
