<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Type;
use App\Models\Sector;
use App\Models\Cluster;
use App\Models\State;
use App\Models\Municipality;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoEmpresaPublica = Type::where('name', 'Empresa Pública')->first();
        

        $sectorAgroindustrial = Sector::where('name', 'Agroindustrial')->first();
        $sectorAlimentos = Sector::where('name', 'Alimentos, Bebidas y Tabaco')->first();
        $sectorPapel = Sector::where('name', 'Papel, Imprenta y Editoriales')->first();
        $sectorTextiles = Sector::where('name', 'Textiles, Vestuario y Cuero')->first();
        

        $clusterNacional = Cluster::where('name', 'Consejo Coordinador Empresarial (CCE)')
            ->where('type', 'Nacional')->first();
        $clusterLocal = Cluster::where('name', 'Consejo Coordinador Empresarial (CCE)')
            ->where('type', 'Local')->first();
        

        $stateAgs = State::where('name', 'Aguascalientes')->first();
        $munAgs = Municipality::where('name', 'Aguascalientes')
            ->where('id_state', $stateAgs?->id)->first();


        if (!$tipoEmpresaPublica || !$sectorAgroindustrial || !$clusterNacional || !$clusterLocal || !$stateAgs || !$munAgs) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero los seeders: Type, Sector, Cluster, State, Municipality');
            return;
        }

        $organizations = [
            [
                'name' => 'Organización de Ejemplo',
                'id_type' => $tipoEmpresaPublica->id,
                'id_sector' => $sectorAgroindustrial->id,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => $clusterNacional->id,
                'id_cluster_local' => $clusterLocal->id,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'scope' => 'Municipal',
            ],
            [
                'name' => 'Organización de Ejemplo2',
                'id_type' => $tipoEmpresaPublica->id,
                'id_sector' => $sectorAlimentos->id,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => $clusterNacional->id,
                'id_cluster_local' => $clusterLocal->id,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'scope' => 'Federal',
            ],
            [
                'name' => 'Organización de Ejemplo3',
                'id_type' => $tipoEmpresaPublica->id,
                'id_sector' => $sectorPapel->id,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => $clusterNacional->id,
                'id_cluster_local' => $clusterLocal->id,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'scope' => 'Estatal',
            ],
            [
                'name' => 'Organización de Ejemplo4',
                'id_type' => $tipoEmpresaPublica->id,
                'id_sector' => $sectorTextiles->id,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => $clusterNacional->id,
                'id_cluster_local' => $clusterLocal->id,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'scope' => 'Internacional',
            ],
            [
                'name' => 'Organización de Ejemplo5',
                'id_type' => $tipoEmpresaPublica->id,
                'id_sector' => $sectorPapel->id,
                'size' => 'Micro (1 a 10 trabajadores)',
                'id_cluster' => $clusterNacional->id,
                'id_cluster_local' => $clusterLocal->id,
                'street' => 'Calle Falsa',
                'external_number' => '123',
                'neighborhood' => 'Colonia Centro',
                'postal_code' => '12345',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'scope' => 'Municipal',
            ],
        ];

        foreach ($organizations as $organization) {
            Organization::updateOrCreate(
                ['name' => $organization['name']],
                $organization
            );
        }

        $this->command->info('Organizaciones creadas/actualizadas correctamente');
    }
}