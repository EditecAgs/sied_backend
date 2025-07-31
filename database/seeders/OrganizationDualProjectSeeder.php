<?php

namespace Database\Seeders;

use App\Models\OrganizationDualProject;
use Illuminate\Database\Seeder;

class OrganizationDualProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationDualProject::updateOrCreate(
            ['id' => 1],
            [
                'id_organization' => 1,
                'id_dual_project' => 1,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 2],
            [
                'id_organization' => 1,
                'id_dual_project' => 2,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 3],
            [
                'id_organization' => 1,
                'id_dual_project' => 3,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 4],
            [
                'id_organization' => 1,
                'id_dual_project' => 4,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 5],
            [
                'id_organization' => 1,
                'id_dual_project' => 5,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 6],
            [
                'id_organization' => 2,
                'id_dual_project' => 6,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 7],
            [
                'id_organization' => 3,
                'id_dual_project' => 7,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 8],
            [
                'id_organization' => 4,
                'id_dual_project' => 8,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 9],
            [
                'id_organization' => 5,
                'id_dual_project' => 9,
            ],
        );
        OrganizationDualProject::updateOrCreate(
            ['id' => 10],
            [
                'id_organization' => 5,
                'id_dual_project' => 10,
            ],
        );
    }
}
