<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Super Admin',
                'password'          => bcrypt('password'),
                'role'              => 'super_admin', // ← UPDATED TO MATCH NEW ROLE
                'email_verified_at' => now(), 
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name'              => 'Team Member One',
                'password'          => bcrypt('password'),
                'role'              => 'team_member',
                'email_verified_at' => now(), 
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name'              => 'Manager',
                'password'          => bcrypt('password'),
                'role'              => 'manager',
                'email_verified_at' => now(), 
            ]
        );

        $this->call([
            BoardSeeder::class,
        ]);
    }
}