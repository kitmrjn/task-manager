<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'              => 'Super Admin',
                'password'          => Hash::make('password123'), // Default test password
                'role'              => 'super_admin',
                'is_active'         => true,
                'email_verified_at' => Carbon::now(), // Bypasses the 'verified' middleware
            ]
        );

        // 2. Create a standard Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Standard Admin',
                'password'          => Hash::make('password123'),
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        // 3. Create a Team Member (to test restricted access)
        User::updateOrCreate(
            ['email' => 'member@example.com'],
            [
                'name'              => 'Team Member',
                'password'          => Hash::make('password123'),
                'role'              => 'team_member',
                'is_active'         => true,
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}