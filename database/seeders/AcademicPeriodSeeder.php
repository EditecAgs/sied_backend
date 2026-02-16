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
            ['name' => 'Semestral'],
            ['name' => 'Cuatrimestral'],
        ];

        foreach ($academicPeriods as $period) {
            AcademicPeriod::updateOrCreate(
                ['name' => $period['name']], 
                $period                     
            );
        }
    }
}
