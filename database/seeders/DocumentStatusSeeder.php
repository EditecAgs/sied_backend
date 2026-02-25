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
            ['name' => 'Convenio Dual Firmado'],
            ['name' => 'Convenio Dual en Trámite'],
        ];

        foreach ($statuses as $status) {
            DocumentStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }

        $this->command->info('Estados de documento creados/actualizados correctamente');
    }
}