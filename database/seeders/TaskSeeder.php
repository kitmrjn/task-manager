<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Team Leader
        $leader = User::create([
            'name' => 'Team Leader Alpha',
            'email' => 'leader@example.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
        ]);

        // 2. Create Subordinates
        $sub1 = User::create([
            'name' => 'Member One',
            'email' => 'member1@example.com',
            'password' => Hash::make('password'),
            'role' => 'subordinate',
        ]);

        $sub2 = User::create([
            'name' => 'Member Two',
            'email' => 'member2@example.com',
            'password' => Hash::make('password'),
            'role' => 'subordinate',
        ]);

        // 3. Create Tasks Assigned by Leader
        Task::create([
            'title' => 'Design Database Schema',
            'description' => 'Create the MySQL tables for the task manager.',
            'status' => 'in-progress',
            'creator_id' => $leader->id,
            'assigned_to' => $sub1->id,
        ]);

        // 4. Create a Task Created by a Subordinate for themselves
        Task::create([
            'title' => 'Update GitHub Readme',
            'description' => 'Add project documentation.',
            'status' => 'todo',
            'creator_id' => $sub2->id,
            'assigned_to' => $sub2->id,
        ]);
    }
}