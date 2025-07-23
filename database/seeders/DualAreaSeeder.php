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
            'Desarrollo de Software',
            'Desarrollo de Maquinaria y Equipo',
            'Desarrollo de Producto',
            'Mejora de Procesos',
            'Planeación Urbana',
            'Políticas Públicas',
            'Programas de Desarrollo Regional y Nacional',
            'Inclusión Social',
            'Tratamiento de Aguas',
            'Conservación del Medio Ambiente',
            'Programas de Salud',
            'Programas de Equidad de Género',
            'Creación de Nuevas Empresas',
            'Automatización de Procesos',
            'Energía Renovable',
            'Elaboración e Implementación de Plan de Mantenimiento de Equipo',
            'Seguridad e Higiene',
        ];
        foreach ($dualAreas as $area) {
            DualArea::create([
                'name' => $area,
            ]);
        }
    }
}
