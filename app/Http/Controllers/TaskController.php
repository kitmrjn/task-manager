<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function move(Request $request, Task $task)
    {
        // Validate that the new column ID was sent and exists
        $request->validate([
            'board_column_id' => 'required|exists:board_columns,id'
        ]);

        // Update the task's column in the database
        $task->update([
            'board_column_id' => $request->board_column_id
        ]);

        return response()->json(['success' => true, 'message' => 'Task moved successfully!']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'board_column_id' => 'required|exists:board_columns,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        // Get the highest order number in this column to place the new task at the bottom
        $highestOrder = Task::where('board_column_id', $request->board_column_id)->max('order');

        Task::create([
            'board_column_id' => $request->board_column_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'order' => $highestOrder ? $highestOrder + 1 : 1, // Place at the bottom
        ]);

        return redirect()->back(); // Reload the dashboard to show the new task
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
        ]);

        return redirect()->back(); // Refresh to show changes
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back(); // Refresh to remove the card
    }
}