<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Villes en Côte d'Ivoire
        $citiesCI = [
            ['city' => 'Abidjan', 'lat' => 5.3600, 'lng' => -4.0083],
            ['city' => 'Bouaké', 'lat' => 7.6900, 'lng' => -5.0300],
            ['city' => 'San Pedro', 'lat' => 4.7500, 'lng' => -6.6400],
            ['city' => 'Yamoussoukro', 'lat' => 6.8200, 'lng' => -5.2700],
            ['city' => 'Korhogo', 'lat' => 9.4600, 'lng' => -5.6300],
        ];

        // Villes à l'étranger
        $worldCities = [
            ['city' => 'Paris', 'country' => 'France', 'lat' => 48.8566, 'lng' => 2.3522],
            ['city' => 'New York', 'country' => 'USA', 'lat' => 40.7128, 'lng' => -74.0060],
            ['city' => 'London', 'country' => 'UK', 'lat' => 51.5074, 'lng' => -0.1278],
            ['city' => 'Tokyo', 'country' => 'Japan', 'lat' => 35.6762, 'lng' => 139.6503],
            ['city' => 'Sydney', 'country' => 'Australia', 'lat' => -33.8688, 'lng' => 151.2093],
        ];

        $types = ['house', 'apartment', 'studio', 'villa'];
        $statuses = ['for_rent', 'for_sale'];
        $availability = ['available', 'en cours', 'sold'];

        // Génération des propriétés en Côte d'Ivoire (500)
        foreach ($citiesCI as $cityData) {
            for ($i = 0; $i < 100; $i++) {
                $lat = $cityData['lat'] + $faker->randomFloat(6, -0.03, 0.03);
                $lng = $cityData['lng'] + $faker->randomFloat(6, -0.03, 0.03);

                Property::create([
                    'slug' => $faker->unique()->slug,
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(),
                    'type' => $faker->randomElement($types),
                    'status' => $faker->randomElement($statuses),
                    'availability_status' => $faker->randomElement($availability),
                    'price' => $faker->numberBetween(20000, 500000),
                    'address' => $faker->address,
                    'city' => $cityData['city'],
                    'postal_code' => $faker->postcode,
                    'country' => "Côte d’Ivoire",
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'bedrooms' => $faker->numberBetween(1, 5),
                    'bathrooms' => $faker->numberBetween(1, 3),
                    'year_built' => $faker->numberBetween(1950, 2025),
                    'features' => json_encode($faker->randomElements(['parking', 'wifi', 'elevator', 'garden'], 3)),
                    'is_featured' => $faker->boolean(10),
                    'viral_score' => $faker->randomFloat(2, 0, 10),
                    'owner_id' => $faker->randomElement([1, 2, 4]),

                ]);
            }
        }

        // Génération des propriétés à l'étranger (200)
        foreach ($worldCities as $cityData) {
            for ($i = 0; $i < 40; $i++) {
                $lat = $cityData['lat'] + $faker->randomFloat(6, -0.05, 0.05);
                $lng = $cityData['lng'] + $faker->randomFloat(6, -0.05, 0.05);

                Property::create([
                    'slug' => $faker->unique()->slug,
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(),
                    'type' => $faker->randomElement($types),
                    'status' => $faker->randomElement($statuses),
                    'availability_status' => $faker->randomElement($availability),
                    'price' => $faker->numberBetween(50000, 800000),
                    'address' => $faker->address,
                    'city' => $cityData['city'],
                    'postal_code' => $faker->postcode,
                    'country' => $cityData['country'],
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'bedrooms' => $faker->numberBetween(1, 6),
                    'bathrooms' => $faker->numberBetween(1, 4),
                    'year_built' => $faker->numberBetween(1900, 2025),
                    'features' => json_encode($faker->randomElements(['parking', 'wifi', 'smart home', 'garden'], 3)),
                    'is_featured' => $faker->boolean(15),
                    'viral_score' => $faker->randomFloat(2, 0, 10),
                    'owner_id' => $faker->randomElement([1, 2, 4]),

                ]);
            }
        }
    }
}
