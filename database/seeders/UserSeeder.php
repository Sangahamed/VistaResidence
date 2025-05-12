<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'account_type' => 'company', // Valeur valide: 'client', 'individual' ou 'company'
            'role' => 'admin', // Valeur valide: 'user', 'agent', 'agency_admin' ou 'admin'
        ]);
        
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->attach($superAdminRole);
        }

        // Créer un admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'account_type' => 'company',
            'role' => 'admin',
        ]);
        
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->attach($adminRole);
        }

        // Créer un gestionnaire
        $manager = User::create([
            'name' => 'Gestionnaire',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'account_type' => 'company',
            'role' => 'agency_admin',
        ]);
        
        $managerRole = Role::where('slug', 'manager')->first();
        if ($managerRole) {
            $manager->roles()->attach($managerRole);
        }

        // Créer un agent
        $agent = User::create([
            'name' => 'Agent',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'account_type' => 'individual',
            'role' => 'agent',
        ]);
        
        $agentRole = Role::where('slug', 'agent')->first();
        if ($agentRole) {
            $agent->roles()->attach($agentRole);
        }

        // Créer un client
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'account_type' => 'client',
            'role' => 'user',
        ]);
        
        $clientRole = Role::where('slug', 'client')->first();
        if ($clientRole) {
            $client->roles()->attach($clientRole);
        }
    }
}
