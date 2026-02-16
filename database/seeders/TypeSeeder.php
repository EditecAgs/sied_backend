<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Empresa Pública'],
            ['name' => 'Empresa Privada'],
            ['name' => 'Dependencia de Gobierno'],
            ['name' => 'Centro de Investigación'],
            ['name' => 'Asociación Civil'],
            ['name' => 'Fundación'],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
