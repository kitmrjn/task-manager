<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Stats ─────────────────────────────────────────────────────────
        // NOTE: Tasks have no 'status' column. Completion is determined by
        // which board column the task sits in (column title contains "done").
        $stats = [
            'total'         => Task::count(),
            'my_tasks'      => Task::where('assigned_to', $user->id)->count(),
            'completed'     => Task::whereHas('column', fn ($q) =>
                                    $q->whereRaw('LOWER(title) LIKE ?', ['%done%'])
                                )->count(),
            'high_priority' => Task::where('priority', 'high')->count(),
        ];

        // ── My Tasks (task table — pre-computed for the view) ─────────────
        $myTasks = Task::with(['column', 'board'])
            ->where('assigned_to', $user->id)
            ->whereHas('column', fn ($q) =>
                $q->whereRaw('LOWER(title) NOT LIKE ?', ['%done%'])
            )
            ->orderBy('due_date')
            ->limit(5)
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
                    default                                                                => 'pill-todo',
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

                return $task;
            });

        // ── Recent Activity ───────────────────────────────────────────────
        // Uses TaskActivity model (as seen in your web.php notifications route)
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

        // ── Completion % ─────────────────────────────────────────────────
        $pct = $stats['total'] > 0
            ? round(($stats['completed'] / $stats['total']) * 100)
            : 0;

        return view('dashboard', compact(
            'stats',
            'myTasks',
            'recentActivity',
            'greeting',
            'firstName',
            'pct',
        ));
    }
}