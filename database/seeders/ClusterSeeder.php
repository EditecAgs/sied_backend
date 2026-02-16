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
            ['name' => 'Consejo Coordinador Empresarial (CCE)', 'type' => 'Nacional'],
            ['name' => 'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)', 'type' => 'Nacional'],
            ['name' => 'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)', 'type' => 'Nacional'],
            ['name' => 'Confederación Patronal de la República Mexicana (COPARMEX)', 'type' => 'Nacional'],
            ['name' => 'Consejo Mexicano de Negocios (CMN)', 'type' => 'Nacional'],
            ['name' => 'Consejo Nacional Agropecuario (CNA)', 'type' => 'Nacional'],
            ['name' => 'Asociación de Bancos de México (ABM)', 'type' => 'Nacional'],
            ['name' => 'Consejo Coordinador Empresarial (CCE)', 'type' => 'Local'],
            ['name' => 'Confederación de Cámaras Industriales de los Estados Unidos Mexicanos (CONCAMIN)', 'type' => 'Local'],
            ['name' => 'Confederación de Cámaras Nacionales de Comercio, Servicios y Turismo (SERVYTUR)', 'type' => 'Local'],
            ['name' => 'Confederación Patronal de la República Mexicana (COPARMEX)', 'type' => 'Local'],
            ['name' => 'Consejo Mexicano de Negocios (CMN)', 'type' => 'Local'],
            ['name' => 'Consejo Nacional Agropecuario (CNA)', 'type' => 'Local'],
            ['name' => 'Asociación de Bancos de México (ABM)', 'type' => 'Local'],
        ];

        foreach ($clusters as $cluster) {
            Cluster::updateOrCreate(
                [
                    'name' => $cluster['name'],
                    'type' => $cluster['type']
                ],
                $cluster
            );
        }

        $this->command->info('Clústeres creados/actualizados correctamente');
    }
}