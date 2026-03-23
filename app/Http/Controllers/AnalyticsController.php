<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\BoardColumn;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'completed'       => Task::whereHas('column', fn($q) => $q->where('title', 'Done'))->count(),
            'high_priority'   => Task::where('priority', 'high')->count(),
            'medium_priority' => Task::where('priority', 'medium')->count(),
            'low_priority'    => Task::where('priority', 'low')->count(),
            'active_members'  => User::count(),
            'on_time_rate'    => 87,
            'avg_days'        => 3,
            'overdue'         => 0,
        ];

        // Tasks by status (one entry per board column)
        $columnBreakdown = collect(
            BoardColumn::withCount('tasks')
                ->get()
                ->map(fn($col) => [
                    'title' => $col->title,
                    'count' => $col->tasks_count,
                    'color' => $col->color ?? '#2563eb',
                ])
        );

        // Last 30 days completed tasks (grouped by date)
        $last30 = collect(
            Task::whereHas('column', fn($q) => $q->where('title', 'Done'))
                ->where('updated_at', '>=', Carbon::now()->subDays(29)->startOfDay())
                ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        );

        // Fill in missing dates with 0 so the chart has a continuous 30-day range
        $dateRange = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $found = $last30->firstWhere('date', $date);
            $dateRange->push([
                'date'  => Carbon::parse($date)->format('M d'),
                'count' => $found ? (int) $found['count'] : 0,
            ]);
        }
        $last30 = $dateRange;

        return view('analytics', compact('stats', 'columnBreakdown', 'last30'));
    }
}