<?php

namespace Database\Seeders;

use App\Models\Subsystem;
use Illuminate\Database\Seeder;

class SubsystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subsytems = [
            'Tecnológico Nacional de México',
            'Universidad Politécnica',
            'Universidad Tecnológica',
            'Universidad Autónoma',
            'Universidad Rosario Castellanos',
            'Instituto Politecnico Nacional',
            'Centro de Investigación',
            'Universidad Privada',
        ];
        foreach ($subsytems as $subsystem) {
            Subsystem::create([
                'name' => $subsystem,
            ]);
        }
    }
}
