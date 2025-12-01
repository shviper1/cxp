<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get the 'user' role
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create test user only if not exists
        if (!User::where('email', 'test@example.com')->exists()) {
            $testUser = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // Assign role to user
            $testUser->assignRole($userRole);

            $this->command->info('✅ Test user created successfully!');
            $this->command->info('📧 Email: test@example.com');
            $this->command->info('🔑 Password: password');
            $this->command->info('👤 Role: user');
        } else {
            $this->command->info('ℹ️  Test user already exists');
        }

        // Create admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            $adminRole = Role::firstOrCreate(['name' => 'super_admin']);

            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $adminUser->assignRole($adminRole);

            $this->command->info('');
            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('📧 Email: admin@example.com');
            $this->command->info('🔑 Password: password');
            $this->command->info('👤 Role: super_admin');
        } else {
            $this->command->info('ℹ️  Admin user already exists');
        }
    }
}
