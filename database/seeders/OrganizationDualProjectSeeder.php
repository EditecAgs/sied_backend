<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\DualProject;
use App\Models\OrganizationDualProject;
use Illuminate\Database\Seeder;

class OrganizationDualProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener organizaciones
        $orgs = [
            'org1' => Organization::where('name', 'Organización de Ejemplo')->first(),
            'org2' => Organization::where('name', 'Organización de Ejemplo2')->first(),
            'org3' => Organization::where('name', 'Organización de Ejemplo3')->first(),
            'org4' => Organization::where('name', 'Organización de Ejemplo4')->first(),
            'org5' => Organization::where('name', 'Organización de Ejemplo5')->first(),
        ];

        // Obtener todos los proyectos duales
        $projects = DualProject::all();

        if ($projects->isEmpty() || !$orgs['org1']) {
            $this->command->error('Ejecuta primero OrganizationSeeder y DualProjectSeeder');
            return;
        }

        // Definir cuántos proyectos asignar a cada organización
        $projectsPerOrg = [
            'org1' => 5,
            'org2' => 1,
            'org3' => 1,
            'org4' => 1,
            'org5' => 2,
        ];

        $projectIndex = 0;

        // Crear relaciones dinámicamente
        foreach ($projectsPerOrg as $orgKey => $count) {
            $org = $orgs[$orgKey];
            if (!$org) continue;

            for ($i = 0; $i < $count; $i++) {
                $project = $projects->get($projectIndex);
                if (!$project) break;

                OrganizationDualProject::updateOrCreate(
                    [
                        'id_organization' => $org->id,
                        'id_dual_project' => $project->id,
                    ],
                    [
                        'id_organization' => $org->id,
                        'id_dual_project' => $project->id,
                    ]
                );

                $projectIndex++;
            }
        }

        $this->command->info('Relaciones creadas correctamente');
    }
}
