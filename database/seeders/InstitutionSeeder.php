<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Institution::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Instituto Tecnológico de Aguascalientes',
                'street' => 'Av. Adolfo López Mateos Ote.',
                'external_number' => '1801',
                'neighborhood' => 'Fracc. Bona Gens',
                'postal_code' => '20256',
                'id_state' => 1,
                'id_municipality' => 1,
                'city' => 'Aguascalientes',
                'id_subsystem' => 1,
                'id_academic_period' => 1,
            ]
        );
        Institution::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Universidad Autónoma de Aguascalientes',
                'street' => 'Calle Falsa',
                'external_number' => 'S/N',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20257',
                'id_state' => 1,
                'id_municipality' => 1,
                'city' => 'Aguascalientes',
                'id_subsystem' => 4,
                'id_academic_period' => 1,
            ]
        );
        Institution::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Instituto Politécnico Nacional',
                'street' => 'Calle Falsa',
                'external_number' => '1802',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20256',
                'id_state' => 7,
                'id_municipality' => 228,
                'city' => 'Aguascalientes',
                'id_subsystem' => 2,
                'id_academic_period' => 1,
            ]
        );
        Institution::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Universidad Politécnica de Aguascalientes',
                'street' => 'Calle Falsa',
                'external_number' => '1803',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20256',
                'id_state' => 1,
                'id_municipality' => 1,
                'city' => 'Aguascalientes',
                'id_subsystem' => 1,
                'id_academic_period' => 2,
            ]
        );
        Institution::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Universidad Autónoma de México',
                'street' => 'Calle Falsa',
                'external_number' => '1801',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20256',
                'id_state' => 7,
                'id_municipality' => 225,
                'city' => 'Aguascalientes',
                'id_subsystem' => 4,
                'id_academic_period' => 1,
            ]
        );
    }
}
