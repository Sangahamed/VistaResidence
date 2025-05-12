<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrateur avec accès à la plupart des fonctionnalités',
            ],
            [
                'name' => 'Gestionnaire',
                'slug' => 'manager',
                'description' => 'Gestionnaire d\'entreprise ou d\'agence',
            ],
            [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Agent immobilier',
            ],
            [
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Client avec accès limité',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
