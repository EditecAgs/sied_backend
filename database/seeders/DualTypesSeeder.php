<?php

namespace Database\Seeders;

use App\Models\DualType;
use Illuminate\Database\Seeder;

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
