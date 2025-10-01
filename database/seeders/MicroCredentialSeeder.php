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
                'name' => 'CCNA: Cisco Certified Network Associate',
                'organization' => 'Cisco',
                'description' => 'Certificación de fundamentos de redes, routing y switching según estándares Cisco.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'CCNP: Cisco Certified Network Professional',
                'organization' => 'Cisco',
                'description' => 'Certificación avanzada en diseño, implementación y resolución de problemas de redes.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Seguridad en Redes',
                'organization' => 'CompTIA',
                'description' => 'Curso sobre protección de redes, firewalls, VPNs y prevención de ataques cibernéticos.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Administración de Servidores',
                'organization' => 'Microsoft',
                'description' => 'Capacitación en gestión de servidores Windows, Active Directory y servicios de red.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'IoT y Redes Industriales',
                'organization' => 'Universidad ABC',
                'description' => 'Curso sobre integración de dispositivos IoT y comunicación en redes industriales.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Fundamentos de Cloud Computing',
                'organization' => 'AWS',
                'description' => 'Introducción a servicios en la nube, arquitecturas y seguridad en entornos cloud.',
            ]
        );

        MicroCredential::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Ciberseguridad Básica',
                'organization' => 'Cisco Networking Academy',
                'description' => 'Curso de seguridad informática, amenazas comunes y buenas prácticas en TICs.',
            ]
        );
    }
}
