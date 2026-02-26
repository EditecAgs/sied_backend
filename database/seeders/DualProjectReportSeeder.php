<?php

namespace Database\Seeders;

use App\Models\DualProjectReport;
use App\Models\DualProject;
use App\Models\DualType;
use App\Models\DualArea;
use App\Models\DocumentStatus;
use App\Models\EconomicSupport;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class DualProjectReportSeeder extends Seeder
{
    public function run(): void
    {
        $instituciones = [
            'institutoAgs' => Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first(),
            'universidadAgs' => Institution::where('name', 'Universidad Autónoma de Aguascalientes')->first(),
            'ipn' => Institution::where('name', 'Instituto Politécnico Nacional')->first(),
            'cdVictoria' => Institution::where('name', 'Instituto Tecnológico de Ciudad Victoria')->first(),
            'sanLuis' => Institution::where('name', 'Instituto Tecnológico de San Luis Potosí')->first(),
        ];

        $tipos = [
            'desarrollo' => DualType::where('name', 'Desarrollo de Proyecto')->first(),
            'rotacion' => DualType::where('name', 'Rotación de Puestos')->first(),
            'practicas' => DualType::where('name', 'Prácticas Profesionales')->first(),
            'practica_area' => DualType::where('name', 'Práctica en Área')->first(),
        ];

        $areas = [
            'software' => DualArea::where('name', 'Desarrollo de Software')->first(),
            'procesos' => DualArea::where('name', 'Mejora de Procesos')->first(),
            'conservacion' => DualArea::where('name', 'Conservación del Medio Ambiente')->first(),
            'energia' => DualArea::where('name', 'Energía Renovable')->first(),
            'aguas' => DualArea::where('name', 'Tratamiento de Aguas')->first(),
            'salud' => DualArea::where('name', 'Programas de Salud')->first(),
            'producto' => DualArea::where('name', 'Desarrollo de Producto')->first(),
            'planeacion' => DualArea::where('name', 'Planeación Urbana')->first(),
        ];

        $statusFirmado = DocumentStatus::where('name', 'Convenio Dual Firmado')->first();
        $apoyoEconomico = EconomicSupport::where('name', 'Apoyo Económico')->first();

        if (!$instituciones['institutoAgs'] || !$tipos['desarrollo'] || !$areas['software'] || !$statusFirmado || !$apoyoEconomico) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero DualProjectSeeder, DualTypesSeeder, DualAreaSeeder, DocumentStatusSeeder y EconomicSupportSeeder');
            return;
        }

        $dualProjects = [];
        foreach ($instituciones as $key => $inst) {
            if ($inst) {
                $dualProjects[$key] = DualProject::where('id_institution', $inst->id)->get();
            } else {
                $dualProjects[$key] = collect();
            }
        }

        $reports = [];

        $projectsAgs = $dualProjects['institutoAgs'];
        foreach ($projectsAgs as $index => $project) {
            $reports[] = [
                'dual_project_id' => $project->id,
                'dual_type_id' => $tipos['desarrollo']->id,
                'name' => 'Proyecto Ags ' . ($index + 1),
                'id_dual_area' => $areas['software']->id,
                'period_start' => now(),
                'period_end' => now()->addMonths(6),
                'status_document' => $statusFirmado->id,
                'economic_support' => $apoyoEconomico->id,
                'amount' => 1000,
                'is_concluded' => false,
                'is_hired' => false,
                'qualification' => null,
                'advisor' => 'interno',
            ];
        }

        foreach ($dualProjects as $projects) {
            foreach ($projects as $project) {
                DualProjectReport::updateOrCreate(
                    ['dual_project_id' => $project->id, 'name' => 'Reporte ' . $project->id],
                    $reports[0] ?? []
                );
            }
        }

        $this->command->info('Reportes de proyectos duales creados/actualizados correctamente');
    }
}
