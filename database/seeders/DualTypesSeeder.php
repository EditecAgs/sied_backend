<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DualType;

class DualTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualType::updateOrCreate(
            ['id' => 1],
            ['name' => 'Residencias']
        );

        DualType::updateOrCreate(
            ['id' => 2],
            ['name' => 'Servicio Social']
        );

        DualType::updateOrCreate(
            ['id' => 3],
            ['name' => 'Dual']
        );

        DualType::updateOrCreate(
            ['id' => 4],
            ['name' => 'Informal']
        );
    }
}
