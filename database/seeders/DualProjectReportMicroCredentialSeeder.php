<?php

namespace Database\Seeders;

use App\Models\DualProjectReportMicroCredential;
use App\Models\DualProjectReport;
use App\Models\MicroCredential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DualProjectReportMicroCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reporte1 = DualProjectReport::where('name', 'Proyecto de Prueba')->first();
        $reporte2 = DualProjectReport::where('name', 'Proyecto de Prueba2')->first();


        $micro1 = MicroCredential::where('name', 'Introducción a Redes de Computadoras')
            ->where('organization', 'Cisco Networking Academy')->first();
        $micro2 = MicroCredential::where('name', 'Fundamentos de Seguridad Informática')
            ->where('organization', 'CompTIA')->first();
        $micro3 = MicroCredential::where('name', 'Administración Básica de Servidores')
            ->where('organization', 'Microsoft Learn')->first();


        if (!$reporte1 || !$reporte2 || !$micro1 || !$micro2 || !$micro3) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero DualProjectReportSeeder y MicroCredentialSeeder');
            
            if (!$reporte1) $this->command->error('Reporte "Proyecto de Prueba" no encontrado');
            if (!$reporte2) $this->command->error('Reporte "Proyecto de Prueba2" no encontrado');
            if (!$micro1) $this->command->error('Microcredencial "Introducción a Redes de Computadoras" no encontrada');
            if (!$micro2) $this->command->error('Microcredencial "Fundamentos de Seguridad Informática" no encontrada');
            if (!$micro3) $this->command->error('Microcredencial "Administración Básica de Servidores" no encontrada');
            
            return;
        }

        $relations = [
            [
                'id_dual_project_report' => $reporte1->id,
                'id_micro_credential' => $micro1->id,
            ],
            [
                'id_dual_project_report' => $reporte1->id,
                'id_micro_credential' => $micro2->id,
            ],
            [
                'id_dual_project_report' => $reporte2->id,
                'id_micro_credential' => $micro3->id,
            ],
        ];

        foreach ($relations as $relation) {
            DualProjectReportMicroCredential::updateOrCreate(
                [
                    'id_dual_project_report' => $relation['id_dual_project_report'],
                    'id_micro_credential' => $relation['id_micro_credential']
                ],
                array_merge($relation, ['id' => Str::uuid()])
            );
        }

        $this->command->info('Relaciones reporte-microcredencial creadas/actualizadas correctamente');
        $this->command->info('Total: ' . count($relations) . ' relaciones procesadas');
    }
}