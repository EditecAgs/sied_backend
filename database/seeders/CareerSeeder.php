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
            ['id' => 1, 'name' => 'Ingeniería Industrial', 'id_institution' => 1],
            ['id' => 2, 'name' => 'Ingeniería en Gestión Empresarial', 'id_institution' => 1],
            ['id' => 3, 'name' => 'Ingeniería en Tecnologías de la Información y las Comunicaciones', 'id_institution' => 1],
            ['id' => 4, 'name' => 'Ingeniería Electrónica', 'id_institution' => 1],
            ['id' => 5, 'name' => 'Ingeniería Eléctrica', 'id_institution' => 1],
            ['id' => 6, 'name' => 'Ingeniería Química', 'id_institution' => 1],
            ['id' => 7, 'name' => 'Ingeniería Mecánica', 'id_institution' => 1],
            ['id' => 8, 'name' => 'Ingeniería en Materiales', 'id_institution' => 1],
            ['id' => 9, 'name' => 'Ingeniería en Semiconductores', 'id_institution' => 1],
            ['id' => 10, 'name' => 'Ingeniería en Ciberseguridad', 'id_institution' => 1],
            ['id' => 11, 'name' => 'Ingeniería en Desarrollo de Aplicaciones', 'id_institution' => 1],
            ['id' => 12, 'name' => 'Licenciatura en Administración', 'id_institution' => 1],
            ['id' => 13, 'name' => 'Licenciatura en Derecho', 'id_institution' => 2],
            ['id' => 14, 'name' => 'Ingeniería Aereoespacial', 'id_institution' => 3],
            ['id' => 15, 'name' => 'Ingeniería Robótica', 'id_institution' => 4],
            ['id' => 16, 'name' => 'Licenciatura en Filosofía y Letras', 'id_institution' => 5],
        ];

        foreach ($careers as $career) {
            Career::updateOrCreate(
                ['id' => $career['id']],
                $career
            );
        }
    }
}
