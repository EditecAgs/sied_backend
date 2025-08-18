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
        Sector::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Agroindustrial',
                'plan_mexico' => 1,
            ]

        );
        Sector::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Silvicultura',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Pesca y Acuacultura',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Minería',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Alimentos, Bebidas y Tabaco',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Textiles, Vestuario y Cuero',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Madera y Productos de Madera',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'Papel, Imprenta y Editoriales',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'Química',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'Plásticos y Caucho',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 11],
            [
                'name' => 'Minerales no Metálicos',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 12],
            [
                'name' => 'Industrias Metálicas Básicas',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 13],
            [
                'name' => 'Productos Metálicos, Maquinaria y Equipos',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 14],
            [
                'name' => 'Construcción',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 15],
            [
                'name' => 'Electricidad, Agua y Gas',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 16],
            [
                'name' => 'Comercio y Turismo',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 17],
            [
                'name' => 'Transporte, Almacenaje y Comunicaciones',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 18],
            [
                'name' => 'Servicios Financieros, Seguros, Actividades Inmobiliarias y de Alquiler',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 19],
            [
                'name' => 'Educación',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 20],
            [
                'name' => 'Tecnologías de la Información y Comunicaciones',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 21],
            [
                'name' => 'Investigación e Innovación',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 22],
            [
                'name' => 'Desarrollo Social',
                'plan_mexico' => 0,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 23],
            [
                'name' => 'Farmacéutico y Dispositivos Médicos',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 24],
            [
                'name' => 'Energía',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 25],
            [
                'name' => 'Calzado',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 26],
            [
                'name' => 'Bienes de consumo y economía circular',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 27],
            [
                'name' => 'Aeroespacial',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 28],
            [
                'name' => 'Semiconductores',
                'plan_mexico' => 1,
            ]
        );
        Sector::updateOrCreate(
            ['id' => 29],
            [
                'name' => 'Automotriz y Electromovilidad',
                'plan_mexico' => 1,
            ]
        );
    }
}
