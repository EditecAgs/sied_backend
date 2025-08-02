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
            ['id' => 1, 'name' => 'Agroindustrial'],
            ['id' => 2, 'name' => 'Silvicultura'],
            ['id' => 3, 'name' => 'Pesca y Acuacultura'],
            ['id' => 4, 'name' => 'Minería'],
            ['id' => 5, 'name' => 'Alimentos, Bebidas y Tabaco'],
            ['id' => 6, 'name' => 'Textiles, Vestuario y Cuero'],
            ['id' => 7, 'name' => 'Madera y Productos de Madera'],
            ['id' => 8, 'name' => 'Papel, Imprenta y Editoriales'],
            ['id' => 9, 'name' => 'Química'],
            ['id' => 10, 'name' => 'Plásticos y Caucho'],
            ['id' => 11, 'name' => 'Minerales no Metálicos'],
            ['id' => 12, 'name' => 'Industrias Metálicas Básicas'],
            ['id' => 13, 'name' => 'Productos Metálicos, Maquinaria y Equipos'],
            ['id' => 14, 'name' => 'Construcción'],
            ['id' => 15, 'name' => 'Electricidad, Agua y Gas'],
            ['id' => 16, 'name' => 'Comercio y Turismo'],
            ['id' => 17, 'name' => 'Transporte, Almacenaje y Comunicaciones'],
            ['id' => 18, 'name' => 'Servicios Financieros, Seguros, Actividades Inmobiliarias y de Alquiler'],
            ['id' => 19, 'name' => 'Educación'],
            ['id' => 20, 'name' => 'Tecnologías de la Información y Comunicaciones'],
            ['id' => 21, 'name' => 'Investigación e Innovación'],
            ['id' => 22, 'name' => 'Desarrollo Social'],
        ];
        foreach ($sectors as $sector) {
            Sector::updateOrCreate(
                ['id' => $sector['id']],
                ['name' => $sector['name']]
            );
        }
    }
}
