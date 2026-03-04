<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User; // Don't forget to import the User model
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['creator', 'assignee'])->get();
        $users = User::all(); // Fetch all users
        
        // Pass both tasks and users to the view
        return view('dashboard', compact('tasks', 'users'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'creator_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // 2. Create the task in the database
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => $request->creator_id,
            'assigned_to' => $request->assigned_to,
            'status' => 'todo', // Default status for new tasks
        ]);

        // 3. Refresh the dashboard
        return redirect('/');
    }

    public function updateStatus(Request $request, Task $task)
    {
        // 1. Ensure the new status is one of our allowed options
        $request->validate([
            'status' => 'required|in:todo,in-progress,done',
        ]);

        // 2. Update the task
        $task->update([
            'status' => $request->status
        ]);

        // 3. Refresh the dashboard
        return redirect('/');
    }
}