<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            [
                'name' => 'Agroindustrial',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Silvicultura',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Pesca y Acuacultura',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Minería',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Alimentos, Bebidas y Tabaco',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Textiles, Vestuario y Cuero',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Madera y Productos de Madera',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Papel, Imprenta y Editoriales',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Química',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Plásticos y Caucho',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Minerales no Metálicos',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Industrias Metálicas Básicas',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Productos Metálicos, Maquinaria y Equipos',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Construcción',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Electricidad, Agua y Gas',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Comercio y Turismo',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Transporte, Almacenaje y Comunicaciones',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Servicios Financieros, Seguros, Actividades Inmobiliarias y de Alquiler',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Educación',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Tecnologías de la Información y Comunicaciones',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Investigación e Innovación',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Desarrollo Social',
                'plan_mexico' => 0,
            ],
            [
                'name' => 'Farmacéutico y Dispositivos Médicos',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Energía',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Calzado',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Bienes de consumo y economía circular',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Aeroespacial',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Semiconductores',
                'plan_mexico' => 1,
            ],
            [
                'name' => 'Automotriz y Electromovilidad',
                'plan_mexico' => 1,
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::updateOrCreate(
                ['name' => $sector['name']],
                $sector
            );
        }

        $this->command->info('Sectores creados/actualizados correctamente');
    }
}