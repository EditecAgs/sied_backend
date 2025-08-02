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
            ['id' => 1, 'name' => 'Consejo Coordinador Empresarial (CCE)'],
            ['id' => 2, 'name' => 'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)'],
            ['id' => 3, 'name' => 'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)'],
            ['id' => 4, 'name' => 'Confederación Patronal de la República Mexicana (COPARMEX)'],
            ['id' => 5, 'name' => 'Consejo Mexicano de Negocios (CMN)'],
            ['id' => 6, 'name' => 'Consejo Nacional Agropecuario (CNA)'],
            ['id' => 7, 'name' => 'Asociación de Bancos de México (ABM)'],
        ];
        foreach ($clusters as $cluster) {
            Cluster::updateOrCreate(
                ['id' => $cluster['id']],
                ['name' => $cluster['name']]
            );
        }
    }
}
