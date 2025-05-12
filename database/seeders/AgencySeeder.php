<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainCompany = Company::where('slug', 'immobilier-xyz')->first();
        $secondCompany = Company::where('slug', 'agence-immobiliere-abc')->first();
        
        if (!$mainCompany || !$secondCompany) {
            return;
        }

        $manager = User::where('email', 'manager@example.com')->first();
        $agent = User::where('email', 'agent@example.com')->first();

        if (!$manager || !$agent) {
            return;
        }

        // Création des agences
        $agencies = [
            [
                'name' => 'Immobilier XYZ Paris',
                'slug' => 'immobilier-xyz-paris',
                'description' => 'Agence principale à Paris',
                'address' => '123 Avenue des Champs-Élysées',
                'city' => 'Paris',
                'zip_code' => '75008',
                'country' => 'France',
                'phone_number' => '01 23 45 67 89',
                'email' => 'paris@immobilier-xyz.com',
                'website' => 'https://paris.immobilier-xyz.com',
                'company_id' => $mainCompany->id,
                'owner_id' => $manager->id,
            ],
            [
                'name' => 'Immobilier XYZ Lyon',
                'slug' => 'immobilier-xyz-lyon',
                'description' => 'Agence à Lyon',
                'address' => '45 Rue de la République',
                'city' => 'Lyon',
                'zip_code' => '69002',
                'country' => 'France',
                'phone_number' => '04 56 78 90 12',
                'email' => 'lyon@immobilier-xyz.com',
                'website' => 'https://lyon.immobilier-xyz.com',
                'company_id' => $mainCompany->id,
                'owner_id' => $manager->id,
            ],
            [
                'name' => 'ABC Luxury Properties',
                'slug' => 'abc-luxury-properties',
                'description' => 'Agence spécialisée dans l\'immobilier de luxe',
                'address' => '45 Rue du Faubourg Saint-Honoré',
                'city' => 'Paris',
                'zip_code' => '75008',
                'country' => 'France',
                'phone_number' => '01 98 76 54 32',
                'email' => 'luxury@agence-abc.com',
                'website' => 'https://luxury.agence-abc.com',
                'company_id' => $secondCompany->id,
                'owner_id' => $manager->id,
            ],
        ];

        foreach ($agencies as $agencyData) {
            $agency = Agency::create($agencyData);
            
            // Attacher l'agent
            if ($agency->slug === 'immobilier-xyz-paris') {
                $agency->agents()->attach($agent->id, [
                    'role' => 'agent',
                    'commission_rate' => 2.5,
                    'bio' => 'Agent immobilier expérimenté spécialisé dans les appartements parisiens',
                    'specialties' => json_encode(['Appartements', 'Immobilier de luxe']),
                ]);
            } elseif ($agency->slug === 'immobilier-xyz-lyon') {
                $agency->agents()->attach($agent->id, [
                    'role' => 'agent',
                    'commission_rate' => 2.0,
                    'bio' => 'Agent immobilier polyvalent',
                    'specialties' => json_encode(['Maisons', 'Investissement locatif']),
                ]);
            }
        }
    }
}
