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
        Subsystem::create([
            'name' => 'Tecnológico Nacional de México',
        ]);
    }
}
