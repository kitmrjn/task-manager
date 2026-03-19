<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

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
        ];

        return view('analytics', compact('stats'));
    }
}