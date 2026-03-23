<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class CalendarController extends Controller
{
    public function index()
    {
        // ── All tasks with due dates (all users) ──────────────────
        $allTasks = Task::with(['assignee', 'column'])
            ->whereNotNull('due_date')
            ->get();

        // ── Group tasks by date for the calendar JS ───────────────
        $tasksByDate = [];
        foreach ($allTasks as $task) {
            $dateKey = \Carbon\Carbon::parse($task->due_date)->format('Y-m-d');
            $tasksByDate[$dateKey][] = [
                'id'       => $task->id,
                'title'    => $task->title,
                'priority' => $task->priority,
                'assignee' => $task->assignee?->name,
                'column'   => $task->column?->title,
                'color'    => match($task->priority) {
                    'high'   => 'red',
                    'medium' => 'amber',
                    'low'    => 'green',
                    default  => 'blue',
                },
            ];
        }

        // ── Upcoming tasks for sidebar (all users, next 30 days) ──
        $upcomingTasks = Task::with(['assignee'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->subDays(1))
            ->where('due_date', '<=', now()->addDays(30))
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // ── Calendar events stored in session ─────────────────────
        $eventsByDate = session('calendar_events', []);

        return view('calendar', compact('upcomingTasks', 'tasksByDate', 'eventsByDate'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date'  => 'required|date',
            'color' => 'nullable|string',
            'type'  => 'nullable|string',
            'time'  => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $events = session('calendar_events', []);
        $dateKey = \Carbon\Carbon::parse($request->date)->format('Y-m-d');

        $events[$dateKey][] = [
            'id'          => uniqid(),
            'title'       => $request->title,
            'color'       => $request->color ?? 'blue',
            'type'        => $request->type ?? 'meeting',
            'time'        => $request->time,
            'description' => $request->description,
        ];

        session(['calendar_events' => $events]);

        return response()->json(['success' => true]);
    }

    public function deleteEvent(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
            'id'   => 'required|string',
        ]);

        $events  = session('calendar_events', []);
        $dateKey = $request->date;

        if (isset($events[$dateKey])) {
            $events[$dateKey] = array_values(
                array_filter($events[$dateKey], fn($e) => $e['id'] !== $request->id)
            );
            if (empty($events[$dateKey])) unset($events[$dateKey]);
        }

        session(['calendar_events' => $events]);

        return response()->json(['success' => true]);
    }
}