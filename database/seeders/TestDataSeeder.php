<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Campaign;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Dummy Campaigns
        $campaign1 = Campaign::firstOrCreate(['name' => 'Inbound Sales Q3']);
        $campaign2 = Campaign::firstOrCreate(['name' => 'Customer Retention']);

        // 2. Create a Manager / Team Leader
        $manager = User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name'              => 'Manager Mike',
                'password'          => Hash::make('password'),
                'role'              => 'manager',
                'is_active'         => true,
                'email_verified_at' => now(),
                'campaign_id'       => $campaign1->id,
            ]
        );

        // 3. Create Team Members (Assigned to Manager Mike)
        $members = [];
        for ($i = 1; $i <= 5; $i++) {
            $members[] = User::firstOrCreate(
                ['email' => "agent{$i}@test.com"],
                [
                    'name'              => "Agent {$i}",
                    'password'          => Hash::make('password'),
                    'role'              => 'team_member',
                    'is_active'         => true,
                    'email_verified_at' => now(),
                    'campaign_id'       => $i % 2 === 0 ? $campaign2->id : $campaign1->id,
                    'team_leader_id'    => $manager->id,
                ]
            );
        }

        // 4. Generate Time Logs for the past 7 days
        foreach ($members as $member) {
            for ($daysAgo = 1; $daysAgo <= 7; $daysAgo++) {
                $date = Carbon::now()->subDays($daysAgo);

                // Skip weekends for more realistic data
                if ($date->isWeekend()) {
                    continue;
                }

                // Randomize Time In (between 8:00 AM and 9:59 AM)
                $timeIn = $date->copy()->setTime(rand(8, 9), rand(0, 59), 0);

                // 80% chance of having completed the shift (Time Out), 20% chance they forgot to time out
                $isComplete = rand(1, 10) > 2; 
                
                // Randomize Time Out (7 to 9 hours after Time In)
                $timeOut = $isComplete ? $timeIn->copy()->addHours(rand(7, 9))->addMinutes(rand(0, 59)) : null;
                
                // Dummy notes
                $notes = $isComplete ? "Handled " . rand(20, 50) . " tasks today. Cleared the inbox and updated trackers." : null;

                TimeLog::updateOrCreate(
                    [
                        'user_id'  => $member->id,
                        'log_date' => $date->toDateString(),
                    ],
                    [
                        'time_in'   => $timeIn,
                        'time_out'  => $timeOut,
                        'eod_notes' => $notes,
                    ]
                );
            }
        }
    }
}