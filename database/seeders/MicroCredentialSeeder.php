<?php

namespace Database\Seeders;

use App\Models\MicroCredential;
use Illuminate\Database\Seeder;

class MicroCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MicroCredential::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Introducción a Redes de Computadoras',
                'organization' => 'Cisco Networking Academy',
                'description' => 'Microcredencial enfocada en conceptos básicos de redes, topologías y protocolos.',
                'type' => 'academic',
                'hours' => 10,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Fundamentos de Seguridad Informática',
                'organization' => 'CompTIA',
                'description' => 'Curso corto sobre amenazas, malware, análisis de riesgos y buenas prácticas.',
                'type' => 'academic',
                'hours' => 8,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Administración Básica de Servidores',
                'organization' => 'Microsoft Learn',
                'description' => 'Microcredencial sobre gestión de usuarios, permisos y servicios esenciales.',
                'type' => 'academic',
                'hours' => 12,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Introducción al Internet de las Cosas (IoT)',
                'organization' => 'AWS Academy',
                'description' => 'Competencia introductoria sobre IoT, sus componentes y aplicaciones.',
                'type' => 'no_academic',
                'hours' => 6,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Ciberseguridad para Usuarios Finales',
                'organization' => 'Google',
                'description' => 'Microcredencial para identificar fraudes, phishing y aplicar prácticas seguras.',
                'type' => 'no_academic',
                'hours' => 4,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Fundamentos de Cloud Computing',
                'organization' => 'AWS Academy',
                'description' => 'Curso breve sobre servicios cloud, modelos de servicio y buenas prácticas.',
                'type' => 'academic',
                'hours' => 9,
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Pensamiento Computacional',
                'organization' => 'Microsoft',
                'description' => 'Microcredencial orientada al aprendizaje de lógica, algoritmos y descomposición de problemas.',
                'type' => 'academic',
                'hours' => 7,
            ]
        );
    }
}
