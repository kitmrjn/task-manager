<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class CalendarController extends Controller
{
    public function index()
    {
        $upcomingTasks = Task::where('assigned_to', auth()->id())
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->subDays(1))
            ->orderBy('due_date')
            ->take(8)
            ->get();

        return view('calendar', compact('upcomingTasks'));
    }
}