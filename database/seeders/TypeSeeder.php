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
            'Empresa Pública',
            'Empresa Privada',
            'Dependencia de Gobierno',
            'Centro de Investigación',
            'Asociación Civil',
            'Fundación',
        ];
        foreach ($types as $type) {
            Type::create([
                'name' => $type,
            ]);
        }
    }
}
