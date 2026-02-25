<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Institution;
use App\Models\Career;
use Illuminate\Database\Seeder;


class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        $universidadAgs = Institution::where('name', 'Universidad Autónoma de Aguascalientes')->first();
        $ipn = Institution::where('name', 'Instituto Politécnico Nacional')->first();
        $institutoSanLuis = Institution::where('name', 'Instituto Tecnológico de San Luis Potosí')->first();


        $carreraIndustrial = Career::where('name', 'Ingeniería Industrial')
            ->where('id_institution', $institutoAgs?->id)->first();
        $carreraDerecho = Career::where('name', 'Licenciatura en Derecho')
            ->where('id_institution', $universidadAgs?->id)->first();
        $carreraAeroespacial = Career::where('name', 'Ingeniería Aeroespacial')
            ->where('id_institution', $ipn?->id)->first();
        $carreraRobotica = Career::where('name', 'Ingeniería Robótica')
            ->where('id_institution', $institutoSanLuis?->id)->first();
        $carreraFilosofia = Career::where('name', 'Licenciatura en Filosofía y Letras')
            ->where('id_institution', $institutoSanLuis?->id)->first();


        if (!$institutoAgs || !$carreraIndustrial) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero InstitutionSeeder y CareerSeeder');
            return;
        }

        $specialties = [
            [
                'name' => 'Manufactura',
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id
            ],
            [
                'name' => 'Derecho Penal',
                'id_institution' => $universidadAgs?->id ?? $institutoAgs->id,
                'id_career' => $carreraDerecho?->id ?? $carreraIndustrial->id
            ],
            [
                'name' => 'Aeronáutica',
                'id_institution' => $ipn?->id ?? $institutoAgs->id,
                'id_career' => $carreraAeroespacial?->id ?? $carreraIndustrial->id
            ],
            [
                'name' => 'Diseño de semiconductores',
                'id_institution' => $institutoSanLuis?->id ?? $institutoAgs->id,
                'id_career' => $carreraRobotica?->id ?? $carreraIndustrial->id
            ],
            [
                'name' => 'Antropología',
                'id_institution' => $institutoSanLuis?->id ?? $institutoAgs->id,
                'id_career' => $carreraFilosofia?->id ?? $carreraIndustrial->id
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                [
                    'name' => $specialty['name'],
                    'id_institution' => $specialty['id_institution'],
                    'id_career' => $specialty['id_career']
                ],
                $specialty
            );
        }

        $this->command->info('Especialidades creadas/actualizadas correctamente');
    }
}