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
            'Convenio Dual Firmado',
            'Convenio Dual en Trámite',
        ];
        foreach ($statuses as $status) {
            DocumentStatus::create([
                'name' => $status,
            ]);
        }
    }
}
