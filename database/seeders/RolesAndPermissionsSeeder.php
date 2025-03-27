<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Création des permissions avec vérification
        $permissions = [
            'can_post_ads',
            'can_rent',
            'manage_enterprise',
            'manage_favorites',
            'make_reservations'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Création des rôles avec vérification
        $roles = [
            'client' => ['can_rent', 'manage_favorites', 'make_reservations'],
            'particulier' => ['can_post_ads'],
            'admin_entreprise' => ['manage_enterprise', 'can_post_ads']
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
