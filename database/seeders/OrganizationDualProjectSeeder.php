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
        OrganizationDualProject::create([
            'id_organization' => 1,
            'id_dual_project' => 1,
        ]);
    }
}
