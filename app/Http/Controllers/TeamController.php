<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;

class TeamController extends Controller
{
    public function index()
    {
        $members   = User::withCount('tasks')->get();
        $teamCount = $members->count();
        $openTasks = Task::whereDoesntHave('column', fn($q) => $q->where('title', 'Done'))->count();

        return view('team', compact('members', 'teamCount', 'openTasks'));
    }
}