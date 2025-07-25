<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            StateSeeder::class,
            MunicipalitySeeder::class,
            SubsystemSeeder::class,
            AcademicPeriodSeeder::class,
            TypeSeeder::class,
            SectorSeeder::class,
            ClusterSeeder::class,
            DualAreaSeeder::class,
            DocumentStatusSeeder::class,
        ]);

        if (App::environment('local')) {
            $this->call([
                InstitutionSeeder::class,
                CareerSeeder::class,
                EconomicSupportSeeder::class,
                OrganizationSeeder::class,
                DualProjectSeeder::class,
                DualProjectReportSeeder::class,
                OrganizationDualProjectSeeder::class,
                SpecialtySeeder::class,
                StudentSeeder::class,
            ]);
        }
    }
}
