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
        Organization::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Organización de Ejemplo',
                'id_type' => 1,
                'id_sector' => 1,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => 1,
                'id_cluster_local' => 8,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => 1,
                'id_municipality' => 1,
                'scope' => 'Municipal',
            ],
        );
        Organization::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Organización de Ejemplo2',
                'id_type' => 1,
                'id_sector' => 5,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => 1,
                'id_cluster_local' => 8,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => 1,
                'id_municipality' => 1,
                'scope' => 'Nacional',
            ],
        );
        Organization::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Organización de Ejemplo3',
                'id_type' => 1,
                'id_sector' => 8,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => 1,
                'id_cluster_local' => 8,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => 1,
                'id_municipality' => 1,
                'scope' => 'Estatal',
            ],
        );
        Organization::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Organización de Ejemplo4',
                'id_type' => 1,
                'id_sector' => 6,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => 1,
                'id_cluster_local' => 8,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => 1,
                'id_municipality' => 1,
                'scope' => 'Internacional',
            ],
        );
        Organization::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Organización de Ejemplo5',
                'id_type' => 1,
                'id_sector' => 8,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => 1,
                'id_cluster_local' => 8,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => 1,
                'id_municipality' => 1,
                'scope' => 'Municipal',
            ],
        );
    }
}
