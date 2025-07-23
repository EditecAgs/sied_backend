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
            'Semestral',
            'Cuatrimestral',
        ];
        foreach ($academicPeriods as $period) {
            AcademicPeriod::create([
                'name' => $period,
            ]);
        }
    }
}
