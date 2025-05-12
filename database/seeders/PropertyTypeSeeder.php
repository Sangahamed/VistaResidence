<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Appartement'],
            ['name' => 'Maison'],
            ['name' => 'Villa'],
            ['name' => 'Terrain'],
            ['name' => 'Local commercial'],
            ['name' => 'Bureau'],
            ['name' => 'Immeuble'],
            ['name' => 'Parking'],
            ['name' => 'Loft'],
            ['name' => 'Château'],
        ];

        foreach ($types as $type) {
            PropertyType::create($type);
        }
    }
}
