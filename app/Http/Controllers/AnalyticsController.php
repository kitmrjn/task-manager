<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\BoardColumn;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $total     = Task::count();
        $completed = Task::where('is_completed', true)->count();

        // Tasks completed per day for last 30 days (for line chart)
        $last30 = collect(range(29, 0))->map(function($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo)->toDateString();
            return [
                'date'  => Carbon::today()->subDays($daysAgo)->format('d/m'),
                'count' => Task::where('is_completed', true)
                               ->whereDate('updated_at', $date)
                               ->count(),
            ];
        });

        // Tasks by column (real columns, not guessed by name)
        $columnBreakdown = BoardColumn::withCount('tasks')
            ->orderByDesc('tasks_count')
            ->get()
            ->map(fn($col) => [
                'title' => $col->title,
                'count' => $col->tasks_count,
                'color' => match($col->color ?? 'gray') {
                    'blue'   => '#2d52c4',
                    'green'  => '#1a8a5a',
                    'yellow' => '#c47c0e',
                    'red'    => '#c0354a',
                    'orange' => '#ea580c',
                    'purple' => '#7c3aed',
                    'pink'   => '#db2777',
                    'teal'   => '#0e9f8e',
                    'indigo' => '#4f46e5',
                    default  => '#6b7491',
                },
            ]);

        // Avg days to complete (from created_at to updated_at for completed tasks)
        $avgDays = Task::where('is_completed', true)
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');

        // On-time rate: completed tasks where due_date >= updated_at
        $onTimeCount = Task::where('is_completed', true)
            ->whereNotNull('due_date')
            ->whereColumn('due_date', '>=', 'updated_at')
            ->count();
        $completedWithDueDate = Task::where('is_completed', true)
            ->whereNotNull('due_date')
            ->count();
        $onTimeRate = $completedWithDueDate > 0
            ? round(($onTimeCount / $completedWithDueDate) * 100)
            : 0;

        $stats = [
            'total'            => $total,
            'completed'        => $completed,
            'high_priority'    => Task::where('priority', 'high')->count(),
            'medium_priority'  => Task::where('priority', 'medium')->count(),
            'low_priority'     => Task::where('priority', 'low')->count(),
            'active_members'   => User::count(),
            'on_time_rate'     => $onTimeRate,
            'avg_days'         => $avgDays ? round($avgDays, 1) : 0,
            'overdue'          => Task::where('is_completed', false)
                                      ->whereNotNull('due_date')
                                      ->where('due_date', '<', now())
                                      ->count(),
        ];

        return view('analytics', compact('stats', 'last30', 'columnBreakdown'));
    }
}