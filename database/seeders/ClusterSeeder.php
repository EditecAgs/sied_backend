<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clusters = [
            ['id' => 1, 'name' => 'Consejo Coordinador Empresarial (CCE)', 'type' => 'Nacional'],
            ['id' => 2, 'name' => 'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)', 'type' => 'Nacional'],
            ['id' => 3, 'name' => 'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)', 'type' => 'Nacional'],
            ['id' => 4, 'name' => 'Confederación Patronal de la República Mexicana (COPARMEX)', 'type' => 'Nacional'],
            ['id' => 5, 'name' => 'Consejo Mexicano de Negocios (CMN)', 'type' => 'Nacional'],
            ['id' => 6, 'name' => 'Consejo Nacional Agropecuario (CNA)', 'type' => 'Nacional'],
            ['id' => 7, 'name' => 'Asociación de Bancos de México (ABM)', 'type' => 'Nacional'],
            ['id' => 8, 'name' => 'Consejo Coordinador Empresarial (CCE)', 'type' => 'Local'],
            ['id' => 9, 'name' => 'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)', 'type' => 'Local'],
            ['id' => 10, 'name' => 'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)', 'type' => 'Local'],
            ['id' => 11, 'name' => 'Confederación Patronal de la República Mexicana (COPARMEX)', 'type' => 'Local'],
            ['id' => 12, 'name' => 'Consejo Mexicano de Negocios (CMN)', 'type' => 'Local'],
            ['id' => 13, 'name' => 'Consejo Nacional Agropecuario (CNA)', 'type' => 'Local'],
            ['id' => 14, 'name' => 'Asociación de Bancos de México (ABM)', 'type' => 'Local'],
        ];
        foreach ($clusters as $cluster) {
            Cluster::updateOrCreate(
                ['id' => $cluster['id']],
                ['name' => $cluster['name'], 'type' => $cluster['type']]
            );
        }
    }
}
