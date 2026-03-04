<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;

class BoardSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch our existing users
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'team_member')->first();

        // 2. Create the main Board owned by the Admin
        $board = Board::create([
            'name' => 'Alpha Launch Web App',
            'description' => 'Main task board for the upcoming release.',
            'user_id' => $admin->id,
        ]);

        // 3. Create the Columns (The Workflow Stages)
        $todo = BoardColumn::create(['board_id' => $board->id, 'title' => 'To Do', 'order' => 1]);
        $inProgress = BoardColumn::create(['board_id' => $board->id, 'title' => 'In Progress', 'order' => 2]);
        $done = BoardColumn::create(['board_id' => $board->id, 'title' => 'Done', 'order' => 3]);

        // 4. Create some Tasks and assign them
        Task::create([
            'board_column_id' => $todo->id,
            'title' => 'Design wireframes',
            'description' => 'Create Figma mockups for the new dashboard.',
            'assigned_to' => $member->id,
            'priority' => 'high',
            'order' => 1,
        ]);

        Task::create([
            'board_column_id' => $inProgress->id,
            'title' => 'Set up Laravel Breeze',
            'description' => 'Install and configure authentication.',
            'assigned_to' => $admin->id,
            'priority' => 'medium',
            'order' => 1,
        ]);

        Task::create([
            'board_column_id' => $done->id,
            'title' => 'Initialize GitHub Repo',
            'description' => 'Invite workmates to the repository.',
            'assigned_to' => $admin->id,
            'priority' => 'low',
            'order' => 1,
        ]);
    }
}