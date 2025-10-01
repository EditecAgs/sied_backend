<?php

namespace Database\Seeders;

use App\Models\DualProjectReportMicroCredential;
use Illuminate\Database\Seeder;

class DualProjectReportMicroCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DualProjectReportMicroCredential::updateOrCreate(
            ['id' => 1],
            [
                'id_dual_project_report' => 1,
                'id_micro_credential' => 1,
            ]
        );

        DualProjectReportMicroCredential::updateOrCreate(
            ['id' => 2],
            [
                'id_dual_project_report' => 1,
                'id_micro_credential' => 2,
            ]
        );

        DualProjectReportMicroCredential::updateOrCreate(
            ['id' => 3],
            [
                'id_dual_project_report' => 2,
                'id_micro_credential' => 3,
            ]
        );
    }
}
