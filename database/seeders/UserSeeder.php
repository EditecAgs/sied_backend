<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $institutoAgs = Institution::where('name', 'Instituto Tecnológico de Aguascalientes')->first();
        $institutoCuliacan = Institution::where('name', 'Instituto Tecnológico de Culiacán')->first();
        $institutoTijuana = Institution::where('name', 'Instituto Tecnológico de Tijuana')->first();
        $institutoCdVictoria = Institution::where('name', 'Instituto Tecnológico de Ciudad Victoria')->first();
        $institutoSanLuis = Institution::where('name', 'Instituto Tecnológico de San Luis Potosí')->first();
        $institutoLeon = Institution::where('name', 'Instituto Tecnológico de León')->first();
        $institutoHuichapan = Institution::where('name', 'Instituto Tecnológico Superior de Huichapan')->first();
        $institutoQueretaro = Institution::where('name', 'Instituto Tecnológico de Querétaro')->first();
        $institutoOrizaba = Institution::where('name', 'Instituto Tecnológico de Orizaba')->first();
        $institutoChetumal = Institution::where('name', 'Instituto Tecnológico de Chetumal')->first();
        $institutoIztapalapa2 = Institution::where('name', 'Instituto Tecnológico de Iztapalapa II')->first();
        $institutoIztapalapa = Institution::where('name', 'Instituto Tecnológico de Iztapalapa')->first();
        $institutoCelaya = Institution::where('name', 'Instituto Tecnológico de Celaya')->first();


        if (!$institutoAgs) {
            $this->command->error('No se encontró el Instituto Tecnológico de Aguascalientes. Ejecuta primero InstitutionSeeder');
            return;
        }

        $users = [
            [
                'name' => 'Admin',
                'lastname' => 'User',
                'email' => 'AdminUser@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 0,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Francisca',
                'lastname' => 'Piña Zazueta',
                'email' => 'Culiacan@example.com',
                'id_institution' => $institutoCuliacan?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'José Guillermo',
                'lastname' => 'Cárdenas López',
                'email' => 'Tijuana@example.com',
                'id_institution' => $institutoTijuana?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Deysi Yesica',
                'lastname' => 'Álvarez Vergara',
                'email' => 'CdVictoria@example.com',
                'id_institution' => $institutoCdVictoria?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'José Diego',
                'lastname' => 'Barcenas Torres',
                'email' => 'SanLuisPotosi@example.com',
                'id_institution' => $institutoSanLuis?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Ma. De Lourdes',
                'lastname' => 'Almaguer Sánchez',
                'email' => 'Leon@example.com',
                'id_institution' => $institutoLeon?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'José Luis',
                'lastname' => 'Gil Vázquez',
                'email' => 'Aguascalientes@example.com',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Imelda',
                'lastname' => 'Pérez Espinoza',
                'email' => 'SuperiorHuichapan@example.com',
                'id_institution' => $institutoHuichapan?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Ramón',
                'lastname' => 'Soto Arreola',
                'email' => 'Queretaro@example.com',
                'id_institution' => $institutoQueretaro?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Maricela',
                'lastname' => 'Gallardo Córdova',
                'email' => 'Orizaba@example.com',
                'id_institution' => $institutoOrizaba?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Mario Vicente',
                'lastname' => 'González Robles',
                'email' => 'Chetumal@example.com',
                'id_institution' => $institutoChetumal?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Atziri Yeraldin',
                'lastname' => 'Merlo Rodríguez',
                'email' => 'Iztapalapa2@example.com',
                'id_institution' => $institutoIztapalapa2?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'José Nino',
                'lastname' => 'Hernández Magdaleno',
                'email' => 'Iztapalapa@example.com',
                'id_institution' => $institutoIztapalapa?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'User',
                'lastname' => 'Celaya',
                'email' => 'Celaya@example.com',
                'id_institution' => $institutoCelaya?->id ?? $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Jorge',
                'lastname' => 'Mondragón',
                'email' => 'sistemas@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Irvin',
                'lastname' => 'Covarrubias',
                'email' => 'industrial@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Francisco',
                'lastname' => 'Louvier',
                'email' => 'jefatura.depi@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Agustin',
                'lastname' => 'Jaime',
                'email' => 'mecanica@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Juan',
                'lastname' => 'Carlos',
                'email' => 'electrica_electronica@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Ulises',
                'lastname' => 'Aguascalientes',
                'email' => 'quimica@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
            [
                'name' => 'Salvador',
                'lastname' => 'Barba Macias',
                'email' => 'salvador.bm@aguascalientes.tecnm.mx',
                'id_institution' => $institutoAgs->id,
                'type' => 1,
                'password' => 'Prueba123$'
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'id' => Str::uuid(),
                    'name' => $userData['name'],
                    'lastname' => $userData['lastname'],
                    'id_institution' => $userData['id_institution'],
                    'type' => $userData['type'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($userData['password']),
                ]
            );
        }

        $this->command->info('Usuarios creados/actualizados correctamente');
    }
}