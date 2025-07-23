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
            ['name' => 'Apoyo Económico', 'description' => 'Apoyo económico para estudiantes'],
            ['name' => 'Apoyo de Transporte', 'description' => 'Apoyo para cubrir gastos de transporte a la unidad dual.'],
            ['name' => 'Apoyo Alimentario', 'description' => 'Apoyo para cubrir gastos de alimentación de los estudiantes.'],
        ];

        foreach ($economicSupports as $support) {
            EconomicSupport::create($support);
        }
    }
}
