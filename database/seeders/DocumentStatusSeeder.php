<?php

namespace Database\Seeders;

use App\Models\DocumentStatus;
use Illuminate\Database\Seeder;

class DocumentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'name' => 'Convenio Dual Firmado'],
            ['id' => 2, 'name' => 'Convenio Dual en Trámite'],
        ];
        foreach ($statuses as $status) {
            DocumentStatus::updateOrCreate(
                ['id' => $status['id']],
                ['name' => $status['name']]
            );
        }
    }
}
