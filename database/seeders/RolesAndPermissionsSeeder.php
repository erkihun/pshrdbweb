<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage posts',
            'manage pages',
            'manage media',
            'manage services',
            'manage signage',
            'manage documents',
            'manage tenders',
            'manage tickets',
            'manage service requests',
            'manage appointments',
            'manage settings',
            'manage sms',
            'manage homepage',
            'manage staff',
            'manage service feedback',
            'manage document requests',
            'manage chat',
            'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $officer = Role::firstOrCreate(['name' => 'Officer']);

        $admin->syncPermissions($permissions);
        $editor->syncPermissions(['view dashboard', 'manage posts', 'manage pages', 'manage media', 'manage services', 'manage documents', 'manage tenders']);
        $officer->syncPermissions(['view dashboard', 'manage tickets', 'manage service requests', 'manage appointments']);
    }
}
