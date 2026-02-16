<?php

namespace Database\Seeders;

use App\Models\Subsystem;
use Illuminate\Database\Seeder;

class SubsystemSeeder extends Seeder
{
    public function run(): void
    {
        $subsystems = [
            ['name' => 'Tecnológico Nacional de México'],
            ['name' => 'Universidad Politécnica'],
            ['name' => 'Universidad Tecnológica'],
            ['name' => 'Universidad Autónoma'],
            ['name' => 'Universidad Rosario Castellanos'],
            ['name' => 'Instituto Politécnico Nacional'],
            ['name' => 'Centro de Investigación'],
            ['name' => 'Universidad Privada'],
        ];

        foreach ($subsystems as $subsystem) {
            Subsystem::updateOrCreate(
                ['name' => $subsystem['name']],
                $subsystem
            );
        }
    }
}
