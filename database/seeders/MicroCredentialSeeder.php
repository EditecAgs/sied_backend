<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MicroCredential;

class MicroCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         MicroCredential::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Certificación Scrum',
                'organization' => 'Empresa XYZ',
                'description' => 'Curso para aprender gestión ágil de proyectos con Scrum.'
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Seguridad Industrial',
                'organization' => 'Empresa ABC',
                'description' => 'Capacitación en normas y procedimientos de seguridad industrial.'
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Herramientas de Desarrollo',
                'organization' => 'Universidad ABC',
                'description' => 'Curso práctico de herramientas de software usadas en la industria.'
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Ética Profesional',
                'organization' => 'Empresa XYZ',
                'description' => 'Curso sobre comportamiento ético y profesional en el trabajo.'
            ]
        );
    }
}
