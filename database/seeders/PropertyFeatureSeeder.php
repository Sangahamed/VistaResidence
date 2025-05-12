<?php

namespace Database\Seeders;

use App\Models\PropertyFeature;
use Illuminate\Database\Seeder;

class PropertyFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['name' => 'Piscine', 'icon' => 'swimming-pool'],
            ['name' => 'Jardin', 'icon' => 'tree'],
            ['name' => 'Garage', 'icon' => 'car'],
            ['name' => 'Balcon', 'icon' => 'door-open'],
            ['name' => 'Terrasse', 'icon' => 'umbrella-beach'],
            ['name' => 'Ascenseur', 'icon' => 'arrow-up'],
            ['name' => 'Climatisation', 'icon' => 'snowflake'],
            ['name' => 'Chauffage central', 'icon' => 'temperature-high'],
            ['name' => 'Sécurité', 'icon' => 'shield-alt'],
            ['name' => 'Vue mer', 'icon' => 'water'],
            ['name' => 'Meublé', 'icon' => 'couch'],
            ['name' => 'Internet haut débit', 'icon' => 'wifi'],
            ['name' => 'Salle de sport', 'icon' => 'dumbbell'],
            ['name' => 'Cave', 'icon' => 'wine-bottle'],
            ['name' => 'Cheminée', 'icon' => 'fire'],
        ];

        foreach ($features as $feature) {
            PropertyFeature::create($feature);
        }
    }
}
