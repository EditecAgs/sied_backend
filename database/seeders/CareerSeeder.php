<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        $universidadAgs = Institution::where('name', 'Universidad Autónoma de Aguascalientes')->first();
        $ipn = Institution::where('name', 'Instituto Politécnico Nacional')->first();
        $institutoTijuana = Institution::where('name', 'Instituto Tecnológico de Tijuana')->first();
        $institutoCdVictoria = Institution::where('name', 'Instituto Tecnológico de Ciudad Victoria')->first();


        if (!$institutoAgs) {
            $this->command->error('No se encontró el Instituto Tecnológico de Aguascalientes. Ejecuta primero InstitutionSeeder');
            return;
        }

        $careers = [
            [
                'name' => 'Ingeniería Industrial',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Gestión Empresarial',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Tecnologías de la Información y las Comunicaciones',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Electrónica',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Eléctrica',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Química',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Mecánica',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Materiales',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Semiconductores',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Ciberseguridad',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería en Desarrollo de Aplicaciones',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Licenciatura en Administración',
                'id_institution' => $institutoAgs->id
            ],
            [
                'name' => 'Licenciatura en Derecho',
                'id_institution' => $universidadAgs?->id ?? $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Aeroespacial',
                'id_institution' => $ipn?->id ?? $institutoAgs->id
            ],
            [
                'name' => 'Ingeniería Robótica',
                'id_institution' => $institutoTijuana?->id ?? $institutoAgs->id
            ],
            [
                'name' => 'Licenciatura en Filosofía y Letras',
                'id_institution' => $institutoCdVictoria?->id ?? $institutoAgs->id
            ],
        ];

        foreach ($careers as $career) {
            Career::updateOrCreate(
                [
                    'name' => $career['name'],
                    'id_institution' => $career['id_institution']
                ],
                $career
            );
        }

        $this->command->info('Carreras creadas/actualizadas correctamente');
    }
}