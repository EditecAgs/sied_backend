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
            'Consejo Coordinador Empresarial (CCE)',
            'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)',
            'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)',
            'Confederación Patronal de la República Mexicana (COPARMEX)',
            'Consejo Mexicano de Negocios (CMN)',
            'Consejo Nacional Agropecuario (CNA)',
            'Asociación de Bancos de México (ABM)',
        ];
        foreach ($clusters as $cluster) {
            Cluster::create([
                'name' => $cluster,
            ]);
        }
    }
}
