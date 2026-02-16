<?php

namespace Database\Seeders;

use App\Models\DualProjectStudent;
use App\Models\Student;
use App\Models\DualProject;
use App\Models\Institution;
use Illuminate\Database\Seeder;


class DualProjectStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $student1 = Student::where('control_number', '25151515')->first();
        $student2 = Student::where('control_number', '36262626')->first();


        $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        

        $dualProjects = DualProject::where('id_institution', $institutoAgs?->id)
            ->orderBy('created_at')
            ->get();
        
        $dualProject1 = $dualProjects->firstWhere('number_student', 2) ?? $dualProjects[0] ?? null;
        
        if (!$student1 || !$student2 || !$dualProject1) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero StudentSeeder y DualProjectSeeder');
            
            if (!$student1) $this->command->error('Estudiante con control 25151515 no encontrado');
            if (!$student2) $this->command->error('Estudiante con control 36262626 no encontrado');
            if (!$dualProject1) $this->command->error('Proyecto dual no encontrado');
            
            return;
        }

        $relations = [
            [
                'id_student' => $student1->id,
                'id_dual_project' => $dualProject1->id,
            ],
            [
                'id_student' => $student2->id,
                'id_dual_project' => $dualProject1->id,
            ],
        ];

        foreach ($relations as $relation) {
            DualProjectStudent::updateOrCreate(
                [
                    'id_student' => $relation['id_student'],
                    'id_dual_project' => $relation['id_dual_project']
                ],
                $relation
            );
        }

        $this->command->info('Relaciones estudiante-proyecto dual creadas/actualizadas correctamente');
    }
}