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
        // Create the Admin User
        \App\Models\User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create a Team Member
        \App\Models\User::factory()->create([
            'name' => 'Team Member One',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => 'team_member',
        ]);

        // Call the new BoardSeeder
        $this->call([
            BoardSeeder::class,
        ]);
    }
}
