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
            ['id' => 1, 'name' => 'Empresa Pública'],
            ['id' => 2, 'name' => 'Empresa Privada'],
            ['id' => 3, 'name' => 'Dependencia de Gobierno'],
            ['id' => 4, 'name' => 'Centro de Investigación'],
            ['id' => 5, 'name' => 'Asociación Civil'],
            ['id' => 6, 'name' => 'Fundación'],
        ];
        foreach ($types as $type) {
            Type::updateOrCreate(
                ['id' => $type['id']],
                ['name' => $type['name']]
            );
        }
    }
}
