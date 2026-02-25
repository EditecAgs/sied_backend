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
        $dualTypes = [
            ['name' => 'Desarrollo de Proyecto'],
            ['name' => 'Rotación de Puestos'],
            ['name' => 'Prácticas Profesionales'],
            ['name' => 'Práctica en Área'],
            ['name' => 'Estadías'],
            ['name' => 'Becarios'],
            ['name' => 'Internado'],
        ];

        foreach ($dualTypes as $type) {
            DualType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }

        $this->command->info('Tipos duales creados/actualizados correctamente');
    }
}