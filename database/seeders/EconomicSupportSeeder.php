<?php

namespace Database\Seeders;

use App\Models\EconomicSupport;
use Illuminate\Database\Seeder;

class EconomicSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $economicSupports = [
            ['id' => 1, 'name' => 'Sin Apoyo Económico', 'description' => 'Aquellos estudiantes que no cuentan con ningún apoyo.'],
            ['id' => 2, 'name' => 'Apoyo Económico', 'description' => 'Apoyo económico para estudiantes'],
            ['id' => 3, 'name' => 'Apoyo de Transporte', 'description' => 'Apoyo para cubrir gastos de transporte a la unidad dual.'],
            ['id' => 4, 'name' => 'Apoyo Alimentario', 'description' => 'Apoyo para cubrir gastos de alimentación de los estudiantes.'],
            ['id'=> 5, 'name' => 'Otro', 'description' => 'Cualquier otro tipo de apoyo económico no especificado.'],
        ];

        foreach ($economicSupports as $economicSupport) {
            EconomicSupport::updateOrCreate(
                ['id' => $economicSupport['id']],
                $economicSupport
            );
        }
    }
}
