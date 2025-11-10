<?php

namespace Database\Seeders;

use App\Models\DualType;
use Illuminate\Database\Seeder;

class DualTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualType::updateOrCreate(
            ['id' => 1],
            ['name' => 'Desarrollo de Proyecto']
        );

        DualType::updateOrCreate(
            ['id' => 2],
            ['name' => 'Rotación de Puestos']
        );

        DualType::updateOrCreate(
            ['id' => 3],
            ['name' => 'Prácticas Profesionales']
        );

        DualType::updateOrCreate(
            ['id' => 4],
            ['name' => 'Práctica en Área']
        );

        DualType::updateOrCreate(
            ['id' => 5],
            ['name' => 'Estadías']
        );

        DualType::updateOrCreate(
            ['id' => 6],
            ['name' => 'Becarios']
        );

        DualType::updateOrCreate(
            ['id' => 7],
            ['name' => 'Internado']
        );
    }
}
