<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BenefitType;

class BenefitTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $benefitTypes = [
            [
                'name' => 'Beneficio Económico',
            ],
            [
                'name' => 'Ahorro de Tiempo',
            ],
        ];

        foreach ($benefitTypes as $benefitType) {
            BenefitType::updateOrCreate(
                ['name' => $benefitType['name']],
                $benefitType
            );
        }

        $this->command->info('Tipos de beneficio creados/actualizados correctamente');
    }
}