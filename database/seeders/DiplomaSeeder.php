<?php

namespace Database\Seeders;
use App\Models\Diploma;
use Illuminate\Database\Seeder;
class DiplomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Diploma::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Diplomado en Desarrollo Web Full Stack',
                'organization' => 'Platzi',
                'description' => 'Diplomado integral que cubre tanto el frontend como el backend del desarrollo web.',
                'type' => 'academic',
                'image' => null,
            ]
        );

        Diploma::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Diplomado en Ciencia de Datos y Machine Learning',
                'organization' => 'Coursera',
                'description' => 'Programa avanzado que abarca análisis de datos, estadística y algoritmos de machine learning.',
                'type' => 'academic',
                'image' => null,
            ]
        );

        Diploma::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Diplomado en Gestión de Proyectos Ágiles',
                'organization' => 'edX',
                'description' => 'Diplomado enfocado en metodologías ágiles como Scrum y Kanban para la gestión de proyectos.',
                'type' => 'no_academic',
                'image' => null,
            ]
        );
    }
}
