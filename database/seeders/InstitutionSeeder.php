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
        Institution::create([
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
        ]);
    }
}
