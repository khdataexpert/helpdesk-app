<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view users',
            'add users',
            'edit users',
            'delete users',
            'view tickets',
            'view own tickets',
            'assignees tickets',
            'add tickets',
            'edit tickets',
            'delete tickets',
            'view projects',
            'view own projects',
            'add projects',
            'edit projects',
            'delete projects',
            'assign projects',
            'view contracts',
            'view own contracts',
            'add contracts',
            'edit contracts',
            'delete contracts',
            'view invoices',
            'view own invoices',
            'add invoices',
            'edit invoices',
            'delete invoices',
            'view teams',
            'add teams',
            'edit teams',
            'delete teams',
            'manage permissions',
            'view Roles',
            'add Roles',
            'edit Roles',
            'delete Roles',
        ];


        foreach ($permissions as $perm) {

            // For API guard
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'api',
            ]);

            // For WEB guard
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }
    }
}
