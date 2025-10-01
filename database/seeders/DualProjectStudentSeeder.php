<?php

namespace Database\Seeders;

use App\Models\DualProjectStudent;
use Illuminate\Database\Seeder;

class DualProjectStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualProjectStudent::updateOrCreate(
            ['id' => 1],
            [
                'id_student' => 1,
                'id_dual_project' => 1,
            ],
        );

        DualProjectStudent::updateOrCreate(
            ['id' => 2],
            [
                'id_student' => 2,
                'id_dual_project' => 1,
            ],
        );
    }
}
