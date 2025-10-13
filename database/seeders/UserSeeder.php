<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Admin',
                'lastname' => 'User',
                'email' => 'AdminUser@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Francisca',
                'lastname' => 'Piña Zazueta',
                'email' => 'Culiacan@example.com',
                'id_institution' => 6,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'José Guillermo',
                'lastname' => 'Cárdenas López',
                'email' => 'Tijuana@example.com',
                'id_institution' => 7,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Deysi Yesica',
                'lastname' => 'Álvarez Vergara',
                'email' => 'CdVictoria@example.com',
                'id_institution' => 8,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'José Diego',
                'lastname' => 'Barcenas Torres',
                'email' => 'SanLuisPotosi@example.com',
                'id_institution' => 9,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Ma. De Lourdes',
                'lastname' => 'Almaguer Sánchez',
                'email' => 'Leon@example.com',
                'id_institution' => 10,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'José Luis',
                'lastname' => 'Gil Vázquez',
                'email' => 'Aguascalientes@example.com',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'Imelda',
                'lastname' => 'Pérez Espinoza ',
                'email' => 'e SuperiorHuichapan@example.com',
                'id_institution' => 11,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'Ramón ',
                'lastname' => 'Soto Arreola',
                'email' => 'Querétaro@example.com',
                'id_institution' => 12,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 11],
            [
                'name' => 'Maricela',
                'lastname' => 'Gallardo Córdova',
                'email' => 'Orizaba@example.com',
                'id_institution' => 13,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 12],
            [
                'name' => 'Mario Vicente ',
                'lastname' => 'González Robles',
                'email' => 'Chetumal@example.com',
                'id_institution' => 14,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );

        User::updateOrCreate(
            ['id' => 13],
            [
                'name' => 'Atziri Yeraldin',
                'lastname' => 'Merlo Rodríguez',
                'email' => 'Iztapalapa2@example.com',
                'id_institution' => 15,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 14],
            [
                'name' => 'José Nino',
                'lastname' => 'Hernández Magdaleno',
                'email' => 'Iztapalapa@example.com',
                'id_institution' => 16,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 14],
            [
                'name' => 'User',
                'lastname' => 'Celaya',
                'email' => 'Celaya@example.com',
                'id_institution' => 17,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 15],
            [
                'name' => 'Jorge',
                'lastname' => 'Mondragón',
                'email' => 'sistemas@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 16],
            [
                'name' => 'Irvin',
                'lastname' => 'Covarrubias',
                'email' => 'industrial@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 17],
            [
                'name' => 'Francisco',
                'lastname' => 'Louvier',
                'email' => 'jefatura.depi@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 18],
            [
                'name' => 'Agustin',
                'lastname' => 'Jaime',
                'email' => 'mecanica@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 19],
            [
                'name' => 'Juan',
                'lastname' => 'Carlos',
                'email' => 'electrica_electronica@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 20],
            [
                'name' => 'Ulises',
                'lastname' => 'Aguascalientes',
                'email' => 'quimica@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
        User::updateOrCreate(
            ['id' => 21],
            [
                'name' => 'Salvador',
                'lastname' => 'Barba Macias',
                'email' => 'salvador.bm@aguascalientes.tecnm.mx',
                'id_institution' => 1,
                'type' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('Prueba123$'),
            ]
        );
    }
}