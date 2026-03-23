<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\BoardColumn;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // ── Date range resolution ──────────────────────────────
        $period = $request->input('period', '30');

        if ($period === 'custom') {
            $from = $request->input('from')
                ? Carbon::parse($request->input('from'))->startOfDay()
                : Carbon::now()->subDays(29)->startOfDay();
            $to = $request->input('to')
                ? Carbon::parse($request->input('to'))->endOfDay()
                : Carbon::now()->endOfDay();
        } else {
            $days = (int) $period;
            $from = Carbon::now()->subDays($days - 1)->startOfDay();
            $to   = Carbon::now()->endOfDay();
        }

        $dayCount = $from->diffInDays($to) + 1;

        // ── Completed tasks in range ───────────────────────────
        $completedTasks = Task::whereHas('column', fn($q) => $q->where('title', 'Done'))
            ->whereBetween('updated_at', [$from, $to])
            ->get();
        $completed = $completedTasks->count();

        // ── Overdue ────────────────────────────────────────────
        $overdue = Task::whereDoesntHave('column', fn($q) => $q->where('title', 'Done'))
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->count();

        // ── On-time rate ───────────────────────────────────────
        $completedWithDue = $completedTasks->filter(fn($t) => $t->due_date);
        $onTime = $completedWithDue->filter(
            fn($t) => Carbon::parse($t->updated_at)->lte(Carbon::parse($t->due_date)->endOfDay())
        )->count();
        $onTimeRate = $completedWithDue->count() > 0
            ? round(($onTime / $completedWithDue->count()) * 100)
            : 0;

        // ── Avg completion days ────────────────────────────────
        $avgDays = $completedTasks->filter(fn($t) => $t->created_at && $t->updated_at)
            ->avg(fn($t) => Carbon::parse($t->created_at)->diffInDays(Carbon::parse($t->updated_at)));
        $avgDays = $avgDays ? round($avgDays) : 0;

        // ── Active members ─────────────────────────────────────
        $activeMembers = User::whereHas('tasks')->count();

        $stats = [
            'completed'       => $completed,
            'high_priority'   => Task::where('priority', 'high')->count(),
            'medium_priority' => Task::where('priority', 'medium')->count(),
            'low_priority'    => Task::where('priority', 'low')->count(),
            'active_members'  => $activeMembers,
            'on_time_rate'    => $onTimeRate,
            'avg_days'        => $avgDays,
            'overdue'         => $overdue,
        ];

        // ── Column breakdown ───────────────────────────────────
        $columnBreakdown = collect(
            BoardColumn::withCount('tasks')->get()->map(fn($col) => [
                'title' => $col->title,
                'count' => $col->tasks_count,
                'color' => $col->color ?? '#2563eb',
            ])
        );

        // ── Chart data (day-by-day in range) ───────────────────
        $last30Raw = Task::whereHas('column', fn($q) => $q->where('title', 'Done'))
            ->whereBetween('updated_at', [$from, $to])
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dateRange = collect();
        for ($i = $dayCount - 1; $i >= 0; $i--) {
            $date  = Carbon::now()->subDays($i)->format('Y-m-d');
            // For custom range, iterate from $from instead
            if ($period === 'custom') {
                $date = $from->copy()->addDays($dayCount - 1 - $i)->format('Y-m-d');
            }
            $found = $last30Raw->firstWhere('date', $date);
            $dateRange->push([
                'date'  => Carbon::parse($date)->format('M d'),
                'count' => $found ? (int) $found['count'] : 0,
            ]);
        }
        $last30 = $dateRange;

        // ── CSV export ─────────────────────────────────────────
        if ($request->input('export') === 'csv') {
            return $this->exportCsv($stats, $columnBreakdown, $last30, $from, $to);
        }

        return view('analytics', compact(
            'stats', 'columnBreakdown', 'last30',
            'period', 'from', 'to', 'dayCount'
        ));
    }

    private function exportCsv($stats, $columnBreakdown, $last30, $from, $to)
    {
        $filename = 'analytics_' . $from->format('Ymd') . '_to_' . $to->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($stats, $columnBreakdown, $last30, $from, $to) {
            $handle = fopen('php://output', 'w');

            // ── Summary stats ──
            fputcsv($handle, ['ANALYTICS REPORT']);
            fputcsv($handle, ['Period', $from->format('M d, Y') . ' – ' . $to->format('M d, Y')]);
            fputcsv($handle, []);
            fputcsv($handle, ['SUMMARY']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Tasks Completed', $stats['completed']]);
            fputcsv($handle, ['On-Time Rate (%)', $stats['on_time_rate']]);
            fputcsv($handle, ['Avg Completion (days)', $stats['avg_days']]);
            fputcsv($handle, ['Active Members', $stats['active_members']]);
            fputcsv($handle, ['Overdue Tasks', $stats['overdue']]);
            fputcsv($handle, ['High Priority Tasks', $stats['high_priority']]);
            fputcsv($handle, ['Medium Priority Tasks', $stats['medium_priority']]);
            fputcsv($handle, ['Low Priority Tasks', $stats['low_priority']]);

            // ── Column breakdown ──
            fputcsv($handle, []);
            fputcsv($handle, ['TASKS BY STATUS']);
            fputcsv($handle, ['Column', 'Task Count']);
            foreach ($columnBreakdown as $col) {
                fputcsv($handle, [$col['title'], $col['count']]);
            }

            // ── Daily chart data ──
            fputcsv($handle, []);
            fputcsv($handle, ['DAILY COMPLETIONS']);
            fputcsv($handle, ['Date', 'Completed']);
            foreach ($last30 as $row) {
                fputcsv($handle, [$row['date'], $row['count']]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}