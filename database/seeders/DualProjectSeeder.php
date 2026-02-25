<?php

namespace Database\Seeders;

use App\Models\DualProject;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class DualProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        // $universidadAgs = Institution::where('name', 'Universidad Autónoma de Aguascalientes')->first();
        // $ipn = Institution::where('name', 'Instituto Politécnico Nacional')->first();
        // $institutoCdVictoria = Institution::where('name', 'Instituto Tecnológico de Ciudad Victoria')->first();
        // $institutoSanLuis = Institution::where('name', 'Instituto Tecnológico de San Luis Potosí')->first();

        // if (!$institutoAgs) {
        //     $this->command->error('No se encontró el Instituto Tecnológico de Aguascalientes. Ejecuta primero InstitutionSeeder');
        //     return;
        // }

        // $dualProjects = [
        //     ['institution' => $institutoAgs, 'number_student' => 2],
        //     ['institution' => $institutoAgs, 'number_student' => 0],
        //     ['institution' => $institutoAgs, 'number_student' => 0],
        //     ['institution' => $institutoAgs, 'number_student' => 0],
        //     ['institution' => $institutoAgs, 'number_student' => 0],
        //     ['institution' => $universidadAgs, 'number_student' => 0],
        //     ['institution' => $universidadAgs, 'number_student' => 0],
        //     ['institution' => $ipn, 'number_student' => 0],
        //     ['institution' => $institutoCdVictoria, 'number_student' => 0],
        //     ['institution' => $institutoSanLuis, 'number_student' => 0],
        // ];

        // foreach ($dualProjects as $project) {
        //     if (!$project['institution']) continue;
        //     DualProject::updateOrCreate(
        //         [
        //             'id_institution' => $project['institution']->id,
        //             'number_student' => $project['number_student']
        //         ],
        //         [
        //             'has_report' => 1,
        //         ]
        //     );
        // }

        // $this->command->info('Proyectos duales creados/actualizados correctamente');

        $this->command->info('DualProjectSeeder está desactivado - No se crearon proyectos');
    }
}
