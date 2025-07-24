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
                'id_dual_project' => 1,
            ]
        );
    }
}
