<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Post permissions
            'create posts', 'edit posts', 'delete posts', 'publish posts',
            'edit others posts', 'delete others posts', 'read private posts',
            // Page permissions
            'create pages', 'edit pages', 'delete pages', 'publish pages',
            'edit others pages', 'delete others pages',
            // Comment permissions
            'moderate comments', 'edit comments',
            // Media permissions
            'upload files', 'edit files', 'delete files',
            // User permissions
            'create users', 'edit users', 'delete users', 'promote users',
            // Admin permissions
            'manage settings', 'manage plugins', 'manage themes',
            'manage custom post types', 'manage menus', 'manage widgets',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $subscriber = Role::firstOrCreate(['name' => 'subscriber']);
        $subscriber->givePermissionTo([]);

        $contributor = Role::firstOrCreate(['name' => 'contributor']);
        $contributor->givePermissionTo([
            'create posts',
        ]);

        $author = Role::firstOrCreate(['name' => 'author']);
        $author->givePermissionTo([
            'create posts', 'edit posts', 'delete posts', 'publish posts',
            'upload files', 'edit files',
        ]);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->givePermissionTo([
            'create posts', 'edit posts', 'delete posts', 'publish posts',
            'edit others posts', 'delete others posts', 'read private posts',
            'create pages', 'edit pages', 'delete pages', 'publish pages',
            'edit others pages', 'delete others pages',
            'moderate comments', 'edit comments',
            'upload files', 'edit files', 'delete files',
            'manage menus', 'manage widgets',
        ]);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created successfully!');
    }
}
