<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $careers = [
            ['name' => 'Ingeniería Industrial', 'id_institution' => 1],
            ['name' => 'Ingeniería en Gestión Empresarial', 'id_institution' => 1],
            ['name' => 'Ingeniería en Tecnologías de la Información y las Comunicaciones', 'id_institution' => 1],
            ['name' => 'Ingeniería Electrónica', 'id_institution' => 1],
            ['name' => 'Ingeniería Eléctrica', 'id_institution' => 1],
            ['name' => 'Ingeniería Química', 'id_institution' => 1],
            ['name' => 'Ingeniería Mecánica', 'id_institution' => 1],
            ['name' => 'Ingeniería en Materiales', 'id_institution' => 1],
            ['name' => 'Ingeniería en Semiconductores', 'id_institution' => 1],
            ['name' => 'Ingeniería en Ciberseguridad', 'id_institution' => 1],
            ['name' => 'Ingeniería en Desarrollo de Aplicaciones', 'id_institution' => 1],
            ['name' => 'Licenciatura en Administración', 'id_institution' => 1],
        ];

        foreach ($careers as $career) {
            Career::create($career);
        }
    }
}
