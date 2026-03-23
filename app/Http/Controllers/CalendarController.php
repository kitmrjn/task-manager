<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\CalendarEvent;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch ALL tasks with due dates (Global for all users)
        $allTasks = Task::with(['assignee', 'column'])
            ->whereNotNull('due_date')
            ->get();

        $tasksByDate = [];
        foreach ($allTasks as $task) {
            $dateKey = Carbon::parse($task->due_date)->format('Y-m-d');
            $tasksByDate[$dateKey][] = [
                'id'       => $task->id,
                'title'    => $task->title,
                'priority' => $task->priority,
                'assignee' => $task->assignee?->name,
                'column'   => $task->column?->title,
                'is_completed' => $task->is_completed, // Added to show strikethrough in JS
                'color'    => match($task->priority) {
                    'high'   => 'red',
                    'medium' => 'amber',
                    'low'    => 'green',
                    default  => 'blue',
                },
            ];
        }

        // 2. Fetch Upcoming tasks for sidebar (Global, next 30 days)
        $upcomingTasks = Task::with(['assignee'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->startOfDay())
            ->where('due_date', '<=', now()->addDays(30))
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // 3. Fetch ALL Calendar events with the CREATOR (Global)
        $allEvents = CalendarEvent::with('user')->get();
        
        $eventsByDate = [];
        foreach ($allEvents as $event) {
            $dateKey = $event->date; 
            $eventsByDate[$dateKey][] = [
                'id'          => $event->id,
                'title'       => $event->title,
                'color'       => $event->color,
                'type'        => $event->type,
                'time'        => $event->time,
                'description' => $event->description,
                'creator'     => $event->user?->name ?? 'System', // This is the new label
                'date'        => $event->date,
            ];
        }

        // Handle AJAX sync for the JS auto-refresh
        if ($request->ajax()) {
            return view('calendar', compact('upcomingTasks', 'tasksByDate', 'eventsByDate'));
        }

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

        CalendarEvent::create([
            'title'       => $request->title,
            'date'        => $request->date,
            'color'       => $request->color ?? 'blue',
            'type'        => $request->type ?? 'meeting',
            'time'        => $request->time,
            'description' => $request->description,
            'user_id'     => auth()->id(), // Current user is the creator
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteEvent(Request $request)
    {
        $request->validate(['id' => 'required']);

        $event = CalendarEvent::find($request->id);
        
        if ($event) {
            $event->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Event not found'], 404);
    }
}