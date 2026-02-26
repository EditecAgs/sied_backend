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
            [
                'name' => 'Sin Apoyo Económico',
                'description' => 'Aquellos estudiantes que no cuentan con ningún apoyo.'
            ],
            [
                'name' => 'Apoyo Económico',
                'description' => 'Apoyo económico para estudiantes'
            ],
            [
                'name' => 'Apoyo de Transporte',
                'description' => 'Apoyo para cubrir gastos de transporte a la unidad dual.'
            ],
            [
                'name' => 'Apoyo Alimentario',
                'description' => 'Apoyo para cubrir gastos de alimentación de los estudiantes.'
            ],
            [
                'name' => 'Otro',
                'description' => 'Cualquier otro tipo de apoyo económico no especificado.'
            ],
        ];

        foreach ($economicSupports as $economicSupport) {
            EconomicSupport::updateOrCreate(
                ['name' => $economicSupport['name']],
                $economicSupport
            );
        }

        $this->command->info('Apoyos económicos creados/actualizados correctamente');
    }
}