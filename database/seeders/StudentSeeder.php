<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::updateOrCreate(
            ['id' => 1],
            [
                'control_number' => '25151515',
                'name' => 'Juan',
                'lastname' => 'Pérez',
                'gender' => 'Masculino',
                'semester' => 5,
                'id_institution' => 1,
                'id_career' => 1,
                'id_specialty' => 1,
            ]
        );

        Student::updateOrCreate(
            ['id' => 2],
            [
                'control_number' => '36262626',
                'name' => 'María',
                'lastname' => 'Gómez',
                'gender' => 'Femenino',
                'semester' => 3,
                'id_institution' => 1,
                'id_career' => 1,
                'id_specialty' => 1,
            ]
        );

        Student::updateOrCreate(
            ['id' => 3],
            [
                'control_number' => '47373737',
                'name' => 'Carlos',
                'lastname' => 'López',
                'gender' => 'Masculino',
                'semester' => 7,
                'id_institution' => 1,
                'id_career' => 1,
                'id_specialty' => 1,
            ]
        );

        Student::updateOrCreate(
            ['id' => 4],
            [
                'control_number' => '58484848',
                'name' => 'Ana',
                'lastname' => 'Martínez',
                'gender' => 'Femenino',
                'semester' => 4,
                'id_institution' => 1,
                'id_career' => 1,
                'id_specialty' => 1,
            ]
        );

        // 5. Estudiante de Administración
        Student::updateOrCreate(
            ['id' => 5],
            [
                'control_number' => '69595959',
                'name' => 'Pedro',
                'lastname' => 'Sánchez',
                'gender' => 'Masculino',
                'semester' => 6,
                'id_institution' => 1,
                'id_career' => 1,
                'id_specialty' => 1,
            ]
        );

        // 6. Estudiante de Psicología
        Student::updateOrCreate(
            ['id' => 6],
            [
                'control_number' => '70606060',
                'name' => 'Laura',
                'lastname' => 'Rodríguez',
                'gender' => 'Femenino',
                'semester' => 2,
                'id_institution' => 2,
                'id_career' => 13,
                'id_specialty' => 2,
            ]
        );

        Student::updateOrCreate(
            ['id' => 7],
            [
                'control_number' => '81717171',
                'name' => 'Diego',
                'lastname' => 'Fernández',
                'gender' => 'Masculino',
                'semester' => 8,
                'id_institution' => 2,
                'id_career' => 13,
                'id_specialty' => 2,
            ]
        );

        Student::updateOrCreate(
            ['id' => 8],
            [
                'control_number' => '92828282',
                'name' => 'Sofía',
                'lastname' => 'Hernández',
                'gender' => 'Femenino',
                'semester' => 5,
                'id_institution' => 3,
                'id_career' => 14,
                'id_specialty' => 3,
            ]
        );

        Student::updateOrCreate(
            ['id' => 9],
            [
                'control_number' => '03939393',
                'name' => 'Jorge',
                'lastname' => 'Díaz',
                'gender' => 'Masculino',
                'semester' => 3,
                'id_institution' => 4,
                'id_career' => 15,
                'id_specialty' => 4,
            ]
        );
        Student::updateOrCreate(
            ['id' => 10],
            [
                'control_number' => '14482256',
                'name' => 'Alejandro',
                'lastname' => 'Ramírez-Cortés',
                'gender' => 'Masculino',
                'semester' => 9,
                'id_institution' => 5,
                'id_career' => 16,
                'id_specialty' => 5,
            ]
        );
    }
}
