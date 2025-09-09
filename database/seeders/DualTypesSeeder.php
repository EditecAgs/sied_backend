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
            ['name' => 'residencias']
        );

        DualType::updateOrCreate(
            ['id' => 2],
            ['name' => 'servicio social']
        );

        DualType::updateOrCreate(
            ['id' => 3],
            ['name' => 'dual']
        );

        DualType::updateOrCreate(
            ['id' => 4],
            ['name' => 'informal']
        );
    }
}
