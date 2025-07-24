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
            ['id' => 1, 'name' => 'Tecnológico Nacional de México'],
            ['id' => 2, 'name' => 'Universidad Politécnica'],
            ['id' => 3, 'name' => 'Universidad Tecnológica'],
            ['id' => 4, 'name' => 'Universidad Autónoma'],
            ['id' => 5, 'name' => 'Universidad Rosario Castellanos'],
            ['id' => 6, 'name' => 'Instituto Politecnico Nacional'],
            ['id' => 7, 'name' => 'Centro de Investigación'],
            ['id' => 8, 'name' => 'Universidad Privada'],
        ];
        foreach ($subsytems as $subsystem) {
            Subsystem::updateOrCreate(
                ['id' => $subsystem['id']],
                ['name' => $subsystem['name']],
            );
        }
    }
}
