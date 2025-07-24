<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialty::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Manufactura',
                'id_institution' => 1,
                'id_career' => 1,
            ],
        );
    }
}
