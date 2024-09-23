<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialType;

class MaterialTypeSeeder extends Seeder
{
    public function run()
    {
        $materialTypes = [
            'Boxes',
            'Machine',
            'Cotton Box',
            'Wire',
            'Coil',
            'Plates',
            'Furniture',
            'Electrical',
            'Eletronics',
            'Tanks',
            'Bags'
        ];

        foreach ($materialTypes as $type) {
            MaterialType::create(['name' => $type]);
        }
    }
}
