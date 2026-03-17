<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BoardColumn;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store a newly created task in storage.
     */
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

        $highestOrder = Task::where('board_column_id', $request->board_column_id)->max('order');

        // Capture the task into a variable here!
        $task = Task::create([
            'board_column_id' => $request->board_column_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'order' => ($highestOrder ?? 0) + 1,
            'is_completed' => false,
        ]);

        // Now $task exists, so this will work:
        $task->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'created this task'
        ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update the specified task in storage.
     */
public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable', 
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'nullable|date',
        'start_date' => 'nullable|date',
        'is_completed' => 'nullable|boolean',
    ]);

    // 1. Capture old values to check for changes
    $oldPriority = $task->priority;
    $oldLeadId = $task->assigned_to;
    $oldStatus = $task->is_completed;

    // 2. Perform the update
    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'assigned_to' => $request->assigned_to ?: null,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
        'start_date' => $request->start_date,
        'is_completed' => $request->has('is_completed') ? (bool)$request->is_completed : $task->is_completed,
    ]);

    // 3. Log specific activities based on what changed
    if ($oldPriority !== $task->priority) {
        $task->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'priority_change',
            'description' => "changed priority to " . strtoupper($task->priority)
        ]);
    }

    if ($task->wasChanged('start_date')) {
    $startDateText = $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('M d, Y') : 'Removed';
    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'start_date_change',
        'description' => "set the start date to $startDateText"
    ]);
}
    if ($oldLeadId != $task->assigned_to) {
        $newLeadName = $task->assignee ? $task->assignee->name : 'Unassigned';
        $task->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'lead_change',
            'description' => "changed lead assignee to $newLeadName"
        ]);
    }

    // Generic log for general title/description updates if nothing specific was caught
    if ($task->wasChanged(['title', 'description', 'due_date'])) {
        $task->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => 'updated task content'
        ]);
    }

    // Inside TaskController@update, add this IF statement before the final return:

if ($task->wasChanged('due_date')) {
    $dateText = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'None';
    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'date_change',
        'description' => "changed the due date to $dateText"
    ]);
}
    return redirect()->back()->with('success', 'Task updated!');
}

    /**
     * Update task position/column via Drag and Drop.
     */
    public function move(Request $request, Task $task)
    {
        $request->validate([
            'board_column_id' => 'required|exists:board_columns,id'
        ]);

        $task->update([
            'board_column_id' => $request->board_column_id
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Task moved successfully!'
        ]);
    }

    /**
     * Toggle completion status and optionally move to Done column.
     */
public function toggleComplete(Task $task)
{
    $task->update(['is_completed' => !$task->is_completed]);

    $status = $task->is_completed ? 'completed' : 'reopened';

    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => $status,
        'description' => "marked this task as $status"
    ]);

    return response()->json(['success' => true]);
}
    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted!');
    }
}