<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TimeLog; // <-- ADDED THIS IMPORT
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // ── Fetch Today's Time Log ────────────────────────────────────────
        $todaysLog = TimeLog::where('user_id', $user->id)
                            ->where('log_date', $today)
                            ->first();

        // ── Get task IDs relevant to this user ────────────────────────────
        // Includes: assigned tasks + collaborating tasks
        $assignedIds      = Task::where('assigned_to', $user->id)->pluck('id');
        $collaboratingIds = \DB::table('task_user')
                                ->where('user_id', $user->id)
                                ->pluck('task_id');
        $myTaskIds = $assignedIds->merge($collaboratingIds)->unique()->values();

        // ── Stats ─────────────────────────────────────────────────────────
        $stats = [
            'total'         => Task::count(),
            'my_tasks'      => $myTaskIds->count(),
            'completed'     => Task::where('is_completed', true)->count(),
            'high_priority' => Task::where('priority', 'high')->count(),
        ];

        // ── My Tasks — assigned + collaborating, not completed ────────────
        $myTasks = Task::with(['column'])
            ->whereIn('id', $myTaskIds)
            ->where('is_completed', false)
            ->orderBy('due_date')
            ->limit(15)
            ->get()
            ->map(function ($task) {
                // Priority CSS class
                $task->priority_class = strtolower($task->priority ?? 'medium');

                // Column / status pill
                $colTitle         = $task->column->title ?? 'To Do';
                $colSlug          = strtolower(str_replace([' ', '-'], '', $colTitle));
                $task->col_title  = $colTitle;
                $task->pill_class = match (true) {
                    str_contains($colSlug, 'done')                                        => 'pill-done',
                    str_contains($colSlug, 'review')                                      => 'pill-review',
                    str_contains($colSlug, 'doing') || str_contains($colSlug, 'progress') => 'pill-doing',
                    default                                                               => 'pill-todo',
                };

                // Due date label + CSS class
                $task->due_class = 'ok';
                $task->due_label = '—';
                if ($task->due_date) {
                    $diff = now()->diffInDays(Carbon::parse($task->due_date), false);
                    if ($diff < 0) {
                        $task->due_class = 'overdue';
                        $task->due_label = 'Overdue';
                    } elseif ($diff <= 2) {
                        $task->due_class = 'soon';
                        $task->due_label = 'Due ' . Carbon::parse($task->due_date)->format('M j');
                    } else {
                        $task->due_class = 'ok';
                        $task->due_label = Carbon::parse($task->due_date)->format('M j, Y');
                    }
                }

                // Start date label
                $task->start_label = $task->start_date
                    ? Carbon::parse($task->start_date)->format('M j')
                    : null;

                return $task;
            });

        // ── Recent Activity ───────────────────────────────────────────────
        $recentActivity = \App\Models\TaskActivity::with(['user', 'task'])
            ->latest()
            ->limit(8)
            ->get();

        // ── Greeting ─────────────────────────────────────────────────────
        $hour     = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default    => 'Good evening',
        };
        $firstName = explode(' ', $user->name)[0];

        // ── Completion % ──────────────────────────────────────────────────
        $pct = $stats['total'] > 0
            ? round(($stats['completed'] / $stats['total']) * 100)
            : 0;

        // ── Chart Data (Last 30 days) ─────────────────────────────────
        $from = Carbon::now()->subDays(29)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        $last30Raw = Task::whereNotNull('completed_at')
            ->whereBetween('completed_at', [$from, $to])
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date  = Carbon::now()->subDays($i)->format('Y-m-d');
            $found = $last30Raw->firstWhere('date', $date);
            $chartData->push([
                'date'  => Carbon::parse($date)->format('M d'),
                'count' => $found ? (int) $found['count'] : 0,
            ]);
        }
        $users = \App\Models\User::orderBy('name')->get();
        $board = \App\Models\Board::with('columns')->first(); 
        // ── FINAL RETURN (Passes everything to the view safely!) ──────────
        return view('dashboard', compact(
            'stats',
            'myTasks',
            'recentActivity',
            'greeting',
            'firstName',
            'pct',
            'todaysLog', // <-- PASSED HERE!
            'users',
            'board',
            'chartData'
        ));
    }

    public function getTasks(Request $request)
    {
        $type = $request->query('type', 'total');
        $user = auth()->user();

        $query = \App\Models\Task::with(['column', 'assignee']);

        if ($type === 'mine')      $query->where('assigned_to', $user->id);
        if ($type === 'completed') $query->where('is_completed', true);
        if ($type === 'high')      $query->where('priority', 'high');

        return response()->json(
            $query->get()->map(fn($t) => [
                'id'       => $t->id,
                'title'    => $t->title,
                'priority' => $t->priority,
                'column'   => $t->column?->title,
                'assignee' => $t->assignee?->name,
                'due_date' => $t->due_date
                    ? \Carbon\Carbon::parse($t->due_date)->format('M d')
                    : null,
            ])
        );
    }
}