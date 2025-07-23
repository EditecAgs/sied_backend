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
            'Agroindustrial',
            'Silvicultura',
            'Pesca y Acuacultura',
            'Minería',
            'Alimentos, Bebidas y Tabaco',
            'Textiles, Vestuario y Cuero',
            'Madera y Productos de Madera',
            'Papel, Imprenta y Editoriales',
            'Química',
            'Plásticos y Caucho',
            'Minerales no Metálicos',
            'Industrias Metálicas Básicas',
            'Productos Metálicos, Maquinaria y Equipos',
            'Construcción',
            'Electricidad, Agua y Gas',
            'Comercio y Turismo',
            'Transporte, Almacenaje y Comunicaciones',
            'Servicios Financieros, Seguros, Actividades Inmobiliarias y de Alquiler',
            'Educación',
            'Tecnologías de la Información y Comunicaciones',
            'Investigación e Innovación',
            'Desarrollo Social',
        ];
        foreach ($sectors as $sector) {
            Sector::create([
                'name' => $sector,
            ]);
        }
    }
}
