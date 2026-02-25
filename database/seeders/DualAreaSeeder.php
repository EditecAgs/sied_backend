<?php

namespace Database\Seeders;

use App\Models\DualArea;
use Illuminate\Database\Seeder;

class DualAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dualAreas = [
            ['name' => 'Desarrollo de Software'],
            ['name' => 'Desarrollo de Maquinaria y Equipo'],
            ['name' => 'Desarrollo de Producto'],
            ['name' => 'Mejora de Procesos'],
            ['name' => 'Planeación Urbana'],
            ['name' => 'Políticas Públicas'],
            ['name' => 'Programas de Desarrollo Regional y Nacional'],
            ['name' => 'Inclusión Social'],
            ['name' => 'Tratamiento de Aguas'],
            ['name' => 'Conservación del Medio Ambiente'],
            ['name' => 'Programas de Salud'],
            ['name' => 'Programas de Equidad de Género'],
            ['name' => 'Creación de Nuevas Empresas'],
            ['name' => 'Automatización de Procesos'],
            ['name' => 'Energía Renovable'],
            ['name' => 'Elaboración e Implementación de Plan de Mantenimiento de Equipo'],
            ['name' => 'Seguridad e Higiene'],
        ];

        foreach ($dualAreas as $area) {
            DualArea::updateOrCreate(
                ['name' => $area['name']],
                $area
            );
        }

        $this->command->info('Áreas duales creadas/actualizadas correctamente');
    }
}