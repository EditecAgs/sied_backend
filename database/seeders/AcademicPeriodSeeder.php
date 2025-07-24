<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicPeriods = [
            ['id' => 1, 'name' => 'Semestral'],
            ['id' => 2, 'name' => 'Cuatrimestral'],
        ];
        foreach ($academicPeriods as $period) {
            AcademicPeriod::updateOrCreate(
                ['id' => $period['id']],
                ['name' => $period['name']]
            );
        }
    }
}
