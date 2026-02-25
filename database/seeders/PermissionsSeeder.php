<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'publish pages',
            'view media',
            'upload media',
            'edit media',
            'delete media',
            'view tickets',
            'reply tickets',
            'change ticket status',
            'delete tickets',
            'view service requests',
            'process service requests',
            'change service request status',
            'delete service requests',
            'view appointments',
            'manage appointments',
            'cancel appointments',
            'view services',
            'create services',
            'edit services',
            'delete services',
            'publish services',
            'view documents',
            'upload documents',
            'edit documents',
            'delete documents',
            'publish documents',
            'download restricted documents',
            'view tenders',
            'create tenders',
            'edit tenders',
            'delete tenders',
            'publish tenders',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
            'view audit logs',
            'view settings',
            'edit settings',
            'view reports',
            'export reports',
            'view alerts',
            'create alerts',
            'edit alerts',
            'delete alerts',
            'publish alerts',
            'view subscribers',
            'manage subscribers',
            'view analytics',
            'manage signage',
            'manage organizations',
            'manage organization profile',
            'manage partners',
            'manage mous',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
