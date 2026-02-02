<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BenefitType;

class BenefitTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BenefitType::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Beneficio Económico',
            ]
        );

        BenefitType::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Ahorro de Tiempo',
            ]
        );
    }
}
