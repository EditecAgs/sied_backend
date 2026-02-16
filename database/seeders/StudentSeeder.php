<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        $universidadAgs = Institution::where('name', 'Universidad Autónoma de Aguascalientes')->first();
        $ipn = Institution::where('name', 'Instituto Politécnico Nacional')->first();
        $institutoCdVictoria = Institution::where('name', 'Instituto Tecnológico de Ciudad Victoria')->first();
        $institutoSanLuis = Institution::where('name', 'Instituto Tecnológico de San Luis Potosí')->first();


        $carreraIndustrial = Career::where('name', 'Ingeniería Industrial')
            ->where('id_institution', $institutoAgs?->id)->first();
        $carreraDerecho = Career::where('name', 'Licenciatura en Derecho')
            ->where('id_institution', $universidadAgs?->id)->first();
        $carreraAeroespacial = Career::where('name', 'Ingeniería Aeroespacial')
            ->where('id_institution', $ipn?->id)->first();
        $carreraRobotica = Career::where('name', 'Ingeniería Robótica')
            ->where('id_institution', $institutoCdVictoria?->id)->first();
        $carreraFilosofia = Career::where('name', 'Licenciatura en Filosofía y Letras')
            ->where('id_institution', $institutoSanLuis?->id)->first();


        $especialidadManufactura = Specialty::where('name', 'Manufactura')
            ->where('id_institution', $institutoAgs?->id)
            ->where('id_career', $carreraIndustrial?->id)->first();
        $especialidadDerechoPenal = Specialty::where('name', 'Derecho Penal')
            ->where('id_institution', $universidadAgs?->id)
            ->where('id_career', $carreraDerecho?->id)->first();
        $especialidadAeronautica = Specialty::where('name', 'Aeronáutica')
            ->where('id_institution', $ipn?->id)
            ->where('id_career', $carreraAeroespacial?->id)->first();
        $especialidadSemiconductores = Specialty::where('name', 'Diseño de semiconductores')
            ->where('id_institution', $institutoSanLuis?->id)
            ->where('id_career', $carreraRobotica?->id)->first();
        $especialidadAntropologia = Specialty::where('name', 'Antropología')
            ->where('id_institution', $institutoSanLuis?->id)
            ->where('id_career', $carreraFilosofia?->id)->first();

        if (!$institutoAgs || !$carreraIndustrial || !$especialidadManufactura) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero InstitutionSeeder, CareerSeeder y SpecialtySeeder');
            return;
        }

        $students = [
            [
                'control_number' => '25151515',
                'name' => 'Juan',
                'lastname' => 'Pérez',
                'gender' => 'Masculino',
                'semester' => 5,
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id,
                'id_specialty' => $especialidadManufactura->id,
            ],
            [
                'control_number' => '36262626',
                'name' => 'María',
                'lastname' => 'Gómez',
                'gender' => 'Femenino',
                'semester' => 3,
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id,
                'id_specialty' => $especialidadManufactura->id,
            ],
            [
                'control_number' => '47373737',
                'name' => 'Carlos',
                'lastname' => 'López',
                'gender' => 'Masculino',
                'semester' => 7,
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id,
                'id_specialty' => $especialidadManufactura->id,
            ],
            [
                'control_number' => '58484848',
                'name' => 'Ana',
                'lastname' => 'Martínez',
                'gender' => 'Femenino',
                'semester' => 4,
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id,
                'id_specialty' => $especialidadManufactura->id,
            ],
            [
                'control_number' => '69595959',
                'name' => 'Pedro',
                'lastname' => 'Sánchez',
                'gender' => 'Masculino',
                'semester' => 6,
                'id_institution' => $institutoAgs->id,
                'id_career' => $carreraIndustrial->id,
                'id_specialty' => $especialidadManufactura->id,
            ],
            [
                'control_number' => '70606060',
                'name' => 'Laura',
                'lastname' => 'Rodríguez',
                'gender' => 'Femenino',
                'semester' => 2,
                'id_institution' => $universidadAgs?->id ?? $institutoAgs->id,
                'id_career' => $carreraDerecho?->id ?? $carreraIndustrial->id,
                'id_specialty' => $especialidadDerechoPenal?->id ?? $especialidadManufactura->id,
            ],
            [
                'control_number' => '81717171',
                'name' => 'Diego',
                'lastname' => 'Fernández',
                'gender' => 'Masculino',
                'semester' => 8,
                'id_institution' => $universidadAgs?->id ?? $institutoAgs->id,
                'id_career' => $carreraDerecho?->id ?? $carreraIndustrial->id,
                'id_specialty' => $especialidadDerechoPenal?->id ?? $especialidadManufactura->id,
            ],
            [
                'control_number' => '92828282',
                'name' => 'Sofía',
                'lastname' => 'Hernández',
                'gender' => 'Femenino',
                'semester' => 5,
                'id_institution' => $ipn?->id ?? $institutoAgs->id,
                'id_career' => $carreraAeroespacial?->id ?? $carreraIndustrial->id,
                'id_specialty' => $especialidadAeronautica?->id ?? $especialidadManufactura->id,
            ],
            [
                'control_number' => '03939393',
                'name' => 'Jorge',
                'lastname' => 'Díaz',
                'gender' => 'Masculino',
                'semester' => 3,
                'id_institution' => $institutoCdVictoria?->id ?? $institutoAgs->id,
                'id_career' => $carreraRobotica?->id ?? $carreraIndustrial->id,
                'id_specialty' => $especialidadSemiconductores?->id ?? $especialidadManufactura->id,
            ],
            [
                'control_number' => '14482256',
                'name' => 'Alejandro',
                'lastname' => 'Ramírez-Cortés',
                'gender' => 'Masculino',
                'semester' => 9,
                'id_institution' => $institutoSanLuis?->id ?? $institutoAgs->id,
                'id_career' => $carreraFilosofia?->id ?? $carreraIndustrial->id,
                'id_specialty' => $especialidadAntropologia?->id ?? $especialidadManufactura->id,
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['control_number' => $student['control_number']], 
                $student
            );
        }

        $this->command->info('Estudiantes creados/actualizados correctamente');
    }
}