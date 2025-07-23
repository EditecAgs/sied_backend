<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Organización de Ejemplo',
            'id_type' => 1,
            'id_sector' => 1,
            'size' => 'Micro (1 a 10 trabajadores)',
            'id_cluster' => 1,
            'street' => 'Calle Falsa',
            'external_number' => '123',
            'neighborhood' => 'Colonia Centro',
            'postal_code' => '12345',
            'id_state' => 1,
            'id_municipality' => 1,
        ]);
    }
}
