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

    }
}
