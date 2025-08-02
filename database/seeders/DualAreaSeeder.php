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
            ['id' => '1', 'name' => 'Desarrollo de Software'],
            ['id' => '2', 'name' => 'Desarrollo de Maquinaria y Equipo'],
            ['id' => '3', 'name' => 'Desarrollo de Producto'],
            ['id' => '4', 'name' => 'Mejora de Procesos'],
            ['id' => '5', 'name' => 'Planeación Urbana'],
            ['id' => '6', 'name' => 'Políticas Públicas'],
            ['id' => '7', 'name' => 'Programas de Desarrollo Regional y Nacional'],
            ['id' => '8', 'name' => 'Inclusión Social'],
            ['id' => '9', 'name' => 'Tratamiento de Aguas'],
            ['id' => '10', 'name' => 'Conservación del Medio Ambiente'],
            ['id' => '11', 'name' => 'Programas de Salud'],
            ['id' => '12', 'name' => 'Programas de Equidad de Género'],
            ['id' => '13', 'name' => 'Creación de Nuevas Empresas'],
            ['id' => '14', 'name' => 'Automatización de Procesos'],
            ['id' => '15', 'name' => 'Energía Renovable'],
            ['id' => '16', 'name' => 'Elaboración e Implementación de Plan de Mantenimiento de Equipo'],
            ['id' => '17', 'name' => 'Seguridad e Higiene'],
        ];
        foreach ($dualAreas as $area) {
            DualArea::updateOrCreate(
                ['id' => $area['id']],
                ['name' => $area['name']]
            );
        }
    }
}
