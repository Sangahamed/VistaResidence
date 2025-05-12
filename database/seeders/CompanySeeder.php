<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();
        $agent = User::where('email', 'agent@example.com')->first();

        // Créer une entreprise principale
        $mainCompany = Company::create([
            'name' => 'Jean Dupont',
            'slug' => 'immobilier-xyz',
            'description' => 'Entreprise immobilière principale',
            'email' => 'contact@immobilier-xyz.com',
            'phone' => '01 23 45 67 89',
            'website' => 'https://www.immobilier-xyz.com',
            'address' => '123 Avenue des Champs-Élysées',
            'city' => 'Paris',
            'zip_code' => '75008',
            'country' => 'France',
            'owner_id' => $superAdmin->id,
        ]);

        // Attacher les utilisateurs à l'entreprise
        $mainCompany->users()->attach([
            $superAdmin->id => ['job_title' => 'Directeur Général', 'is_admin' => true],
            $admin->id => ['job_title' => 'Administrateur', 'is_admin' => true],
            $manager->id => ['job_title' => 'Responsable Commercial', 'is_admin' => false],
            $agent->id => ['job_title' => 'Agent Immobilier', 'is_admin' => false],
        ]);

        // Attacher tous les modules à l'entreprise principale
        $modules = Module::all();
        $moduleData = [];
        foreach ($modules as $module) {
            $moduleData[$module->id] = [
                'is_enabled' => true,
                'settings' => json_encode([]),
                'expires_at' => now()->addYear(),
            ];
        }
        $mainCompany->modules()->attach($moduleData);

        // Créer une deuxième entreprise
        $secondCompany = Company::create([
            'name' => 'Marie Martin',
            'slug' => 'agence-immobiliere-abc',
            'description' => 'Agence immobilière spécialisée dans les biens de luxe',
            'email' => 'contact@agence-abc.com',
            'phone' => '01 98 76 54 32',
            'website' => 'https://www.agence-abc.com',
            'address' => '45 Rue du Faubourg Saint-Honoré',
            'city' => 'Paris',
            'zip_code' => '75008',
            'country' => 'France',
            'owner_id' => $admin->id,
        ]);

        // Attacher les utilisateurs à la deuxième entreprise
        $secondCompany->users()->attach([
            $admin->id => ['job_title' => 'Directeur', 'is_admin' => true],
            $manager->id => ['job_title' => 'Responsable des Ventes', 'is_admin' => false],
        ]);

        // Attacher certains modules à la deuxième entreprise
        $coreModules = Module::where('is_core', true)->get();
        $moduleData = [];
        foreach ($coreModules as $module) {
            $moduleData[$module->id] = [
                'is_enabled' => true,
                'settings' => json_encode([]),
                'expires_at' => now()->addYear(),
            ];
        }
        $secondCompany->modules()->attach($moduleData);
    }
}
