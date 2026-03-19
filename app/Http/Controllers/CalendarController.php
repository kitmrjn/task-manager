<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get assigned + collaborator task IDs
        $assignedIds      = Task::where('assigned_to', $user->id)->pluck('id');
        $collaboratingIds = \DB::table('task_user')
                                ->where('user_id', $user->id)
                                ->pluck('task_id');
        $myTaskIds = $assignedIds->merge($collaboratingIds)->unique()->values();

        // Tasks with due dates
        $tasks = Task::whereIn('id', $myTaskIds)
            ->whereNotNull('due_date')
            ->with('column')
            ->get();

        // Upcoming tasks — next 30 days
        $upcomingTasks = Task::whereIn('id', $myTaskIds)
            ->whereNotNull('due_date')
            ->where('is_completed', false)
            ->where('due_date', '>=', now()->startOfDay())
            ->orderBy('due_date')
            ->with('column')
            ->take(8)
            ->get();

        // Group tasks by date for JS
        $tasksByDate = $tasks->groupBy(function($task) {
            return Carbon::parse($task->due_date)->format('Y-m-d');
        })->map(function($group) {
            return $group->map(fn($t) => [
                'id'           => $t->id,
                'title'        => $t->title,
                'priority'     => $t->priority,
                'is_completed' => $t->is_completed,
                'column'       => $t->column?->title,
                'type'         => 'task',
            ]);
        });

        // User's custom calendar events
        $calendarEvents = CalendarEvent::where('user_id', $user->id)->get();

        // Group calendar events by date
        $eventsByDate = $calendarEvents->groupBy(function($e) {
            return Carbon::parse($e->date)->format('Y-m-d');
        })->map(function($group) {
            return $group->map(fn($e) => [
                'id'          => $e->id,
                'title'       => $e->title,
                'description' => $e->description,
                'time'        => $e->time,
                'type'        => $e->type,
                'color'       => $e->color,
            ]);
        });

        return view('calendar', compact('upcomingTasks', 'tasksByDate', 'eventsByDate'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'time'        => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'type'        => 'required|in:meeting,note,reminder',
            'color'       => 'required|in:blue,green,red,amber,purple',
        ]);

        CalendarEvent::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'date'        => $request->date,
            'time'        => $request->time,
            'description' => $request->description,
            'type'        => $request->type,
            'color'       => $request->color,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyEvent(CalendarEvent $event)
    {
        if ($event->user_id !== auth()->id()) abort(403);
        $event->delete();
        return response()->json(['success' => true]);
    }
}