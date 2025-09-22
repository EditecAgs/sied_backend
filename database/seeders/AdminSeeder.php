<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Admin',
                'lastname' => 'User',
                'email' => 'admin@aguascalientes.tecnm.mx',
                'password' => Hash::make('9yYMN1mWCF1yJco'),
                'id_institution' => 1,
                'type' => 0, // 0 = administrador
            ]
        );
    }
}
