<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Get all resources and create permissions
        $resources = FilamentShield::getResources();

        foreach ($resources as $resource) {
            $permissions = FilamentShield::getResourcePermissions($resource['resourceFqcn']);

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
        }

        // Get all pages and create permissions
        $pages = FilamentShield::getPages();

        foreach ($pages as $page) {
            Permission::firstOrCreate(['name' => 'view_' . $page]);
        }

        // Get all widgets and create permissions
        $widgets = FilamentShield::getWidgets();

        foreach ($widgets as $widget) {
            Permission::firstOrCreate(['name' => 'view_' . $widget]);
        }

        // Create a super admin role with all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

        // Create a basic panel user role
        $panelUser = Role::firstOrCreate(['name' => 'panel_user']);

        // Get all permissions and assign to super admin
        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);
    }
}
