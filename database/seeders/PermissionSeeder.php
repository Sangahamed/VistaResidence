<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Définir les permissions
        $permissions = [
            // Permissions générales
            ['name' => 'Voir le tableau de bord', 'slug' => 'view-dashboard'],
            
            // Permissions pour les entreprises
            ['name' => 'Voir les entreprises', 'slug' => 'view-companies'],
            ['name' => 'Gérer les entreprises', 'slug' => 'manage-companies'],
            ['name' => 'Gérer toutes les entreprises', 'slug' => 'manage-all-companies'],
            
            // Permissions pour les agences
            ['name' => 'Voir les agences', 'slug' => 'view-agencies'],
            ['name' => 'Gérer les agences', 'slug' => 'manage-agencies'],
            
            // Permissions pour les agents
            ['name' => 'Voir les agents', 'slug' => 'view-agents'],
            ['name' => 'Gérer les agents', 'slug' => 'manage-agents'],
            
            // Permissions pour les équipes
            ['name' => 'Voir les équipes', 'slug' => 'view-teams'],
            ['name' => 'Gérer les équipes', 'slug' => 'manage-teams'],
            
            // Permissions pour les projets
            ['name' => 'Voir les projets', 'slug' => 'view-projects'],
            ['name' => 'Gérer les projets', 'slug' => 'manage-projects'],
            
            // Permissions pour les tâches
            ['name' => 'Voir les tâches', 'slug' => 'view-tasks'],
            ['name' => 'Gérer les tâches', 'slug' => 'manage-tasks'],
            
            // Permissions pour les propriétés
            ['name' => 'Voir les propriétés', 'slug' => 'view-properties'],
            ['name' => 'Gérer les propriétés', 'slug' => 'manage-properties'],
            ['name' => 'Publier des propriétés', 'slug' => 'publish-properties'],
            
            // Permissions pour les leads
            ['name' => 'Voir les leads', 'slug' => 'view-leads'],
            ['name' => 'Gérer les leads', 'slug' => 'manage-leads'],
            
            // Permissions pour les utilisateurs
            ['name' => 'Voir les utilisateurs', 'slug' => 'view-users'],
            ['name' => 'Gérer les utilisateurs', 'slug' => 'manage-users'],
            
            // Permissions pour les rôles et permissions
            ['name' => 'Voir les rôles', 'slug' => 'view-roles'],
            ['name' => 'Gérer les rôles', 'slug' => 'manage-roles'],
            
            // Permissions pour les modules
            ['name' => 'Voir les modules', 'slug' => 'view-modules'],
            ['name' => 'Gérer les modules', 'slug' => 'manage-modules'],
            
            // Permissions pour les rapports
            ['name' => 'Voir les rapports', 'slug' => 'view-reports'],
            ['name' => 'Gérer les rapports', 'slug' => 'manage-reports'],
            
            // Permissions pour les paramètres
            ['name' => 'Voir les paramètres', 'slug' => 'view-settings'],
            ['name' => 'Gérer les paramètres', 'slug' => 'manage-settings'],
        ];

        // Créer les permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Récupérer les rôles
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $manager = Role::where('slug', 'manager')->first();
        $agent = Role::where('slug', 'agent')->first();
        $client = Role::where('slug', 'client')->first();

        // Attribuer toutes les permissions au super admin
        $superAdmin->permissions()->attach(Permission::all());

        // Attribuer les permissions à l'admin
        $adminPermissions = Permission::whereNotIn('slug', [
            'manage-all-companies',
            'manage-modules',
            'manage-roles',
        ])->get();
        $admin->permissions()->attach($adminPermissions);

        // Attribuer les permissions au manager
        $managerPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-companies',
            'view-agencies',
            'view-agents',
            'manage-agents',
            'view-teams',
            'manage-teams',
            'view-projects',
            'manage-projects',
            'view-tasks',
            'manage-tasks',
            'view-properties',
            'manage-properties',
            'publish-properties',
            'view-leads',
            'manage-leads',
            'view-users',
            'view-reports',
        ])->get();
        $manager->permissions()->attach($managerPermissions);

        // Attribuer les permissions à l'agent
        $agentPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-companies',
            'view-agencies',
            'view-agents',
            'view-teams',
            'view-projects',
            'view-tasks',
            'manage-tasks',
            'view-properties',
            'manage-properties',
            'view-leads',
            'manage-leads',
        ])->get();
        $agent->permissions()->attach($agentPermissions);

        // Attribuer les permissions au client
        $clientPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-properties',
        ])->get();
        $client->permissions()->attach($clientPermissions);
    }
}
