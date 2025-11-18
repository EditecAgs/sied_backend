<?php

namespace Database\Seeders;
use App\Models\Certification;
use Illuminate\Database\Seeder;
class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Certification::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Certificación en Fundamentos de Programación',
                'organization' => 'freeCodeCamp',
                'description' => 'Certificación que cubre los conceptos básicos de programación y desarrollo web.',
                'type' => 'academic',
            ]
        );

        Certification::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Certificación en Marketing Digital',
                'organization' => 'Google Digital Garage',
                'description' => 'Programa que abarca estrategias y herramientas clave para el marketing digital efectivo.',
                'type' => 'no_academic',
            ]
        );

        Certification::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Certificación en Seguridad Informática',
                'organization' => 'Cybrary',
                'description' => 'Certificación enfocada en prácticas y principios de seguridad informática.',
                'type' => 'academic',
            ]
        );
    }
}