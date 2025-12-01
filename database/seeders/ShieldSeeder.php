<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions and roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create basic permissions that Shield would generate
        $permissions = [
            // User permissions
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',

            // Role permissions
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',

            // Permission permissions
            'view_any_permission',
            'view_permission',
            'create_permission',
            'update_permission',
            'delete_permission',

            // Post permissions
            'view_any_post',
            'view_post',
            'create_post',
            'update_post',
            'delete_post',

            // Category permissions
            'view_any_category',
            'view_category',
            'create_category',
            'update_category',
            'delete_category',

            // Section permissions
            'view_any_section',
            'view_section',
            'create_section',
            'update_section',
            'delete_section',

            // Country permissions
            'view_any_country',
            'view_country',
            'create_country',
            'update_country',
            'delete_country',

            // State permissions
            'view_any_state',
            'view_state',
            'create_state',
            'update_state',
            'delete_state',

            // City permissions
            'view_any_city',
            'view_city',
            'create_city',
            'update_city',
            'delete_city',

            // SiteSetting permissions
            'view_any_site_setting',
            'view_site_setting',
            'create_site_setting',
            'update_site_setting',
            'delete_site_setting',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create default Shield roles
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
        }
    }
}
