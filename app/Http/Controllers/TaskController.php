<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Fetch all tasks and include the linked creator and assignee data
        $tasks = Task::with(['creator', 'assignee'])->get();
        
        // Send the data to a view named 'dashboard'
        return view('dashboard', compact('tasks'));
    }
}