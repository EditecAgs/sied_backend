<?php

namespace Database\Seeders;

use App\Models\DualProject;
use Illuminate\Database\Seeder;

class DualProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualProject::updateOrCreate(
            ['id' => 1],
            [
                'has_report' => 1,
                'id_institution' => 1,
            ]
        );
    }
}