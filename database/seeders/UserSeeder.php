<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuarios existentes
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Pepito',
                'lastname' => 'Pérez',
                'email' => 'pepito@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Mielsanmarcos24.')
            ]
        );

        User::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Admin',
                'lastname' => 'User',
                'email' => 'AdminUser@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$')
            ]
        );

        // Admin por institución
        User::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Admin',
                'lastname' => 'UAA',
                'email' => 'adminUAA@example.com',
                'id_institution' => 2,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123$')
            ]
        );

        User::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Admin',
                'lastname' => 'IPN',
                'email' => 'adminIPN@example.com',
                'id_institution' => 3,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123$')
            ]
        );

        User::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Admin',
                'lastname' => 'UPA',
                'email' => 'adminUPA@example.com',
                'id_institution' => 4,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123$')
            ]
        );

        User::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Admin',
                'lastname' => 'UNAM',
                'email' => 'adminUNAM@example.com',
                'id_institution' => 5,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123$')
            ]
        );
    }
}
