<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BoardColumn;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    \Log::info('UPDATE REQUEST', $request->all());
    $request->validate([
        'board_column_id' => 'required|exists:board_columns,id',
        'title'           => 'required|string|max:255',
        'description'     => 'nullable|string',
        'assigned_to'     => 'nullable|exists:users,id',
        'priority'        => 'required|in:low,medium,high',
        'due_date'        => 'nullable|date',
        'start_date'      => 'nullable|date',
        'is_completed'    => 'nullable|boolean',
    ]);

    $oldPriority = $task->priority;
    $oldLeadId   = $task->assigned_to;

    $task->update([
        'board_column_id' => $request->board_column_id,
        'title'           => $request->title,
        'description'     => $request->description,
        'assigned_to'     => $request->assigned_to ?: null,
        'priority'        => $request->priority,
        'due_date'        => $request->due_date ?: null,
        'start_date'      => $request->start_date ?: null,
        'is_completed'    => $request->has('is_completed')
                                ? (bool) $request->is_completed
                                : $task->is_completed,
    ]);

    // Parse collaborators
    $collaboratorIds = [];
    $raw = $request->input('collaborators');
    if ($raw && $raw !== '[]') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $collaboratorIds = array_filter(array_map('intval', $decoded));
        }
    }
    $task->members()->sync($collaboratorIds);

// Activity logs
    if ($oldPriority !== $task->priority) {
        $task->activities()->create([
            'user_id'     => auth()->id(),
            'type'        => 'updated', // Add 'type' for dashboard icons
            'action'      => 'priority_change',
            'description' => 'changed priority to ' . strtoupper($task->priority)
        ]);
    }

    if ($task->wasChanged('due_date')) {
        $dateText = $task->due_date
            ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
            : 'None';
        $task->activities()->create([
            'user_id'     => auth()->id(),
            'type'        => 'updated', 
            'action'      => 'date_change',
            'description' => "changed the due date to $dateText"
        ]);
    }

    if ($oldLeadId != $task->assigned_to) {
        $newLeadName = $task->assignee ? $task->assignee->name : 'Unassigned';
        $task->activities()->create([
            'user_id'     => auth()->id(),
            'type'        => 'assigned', // Use 'assigned' type for the icon
            'action'      => 'lead_change',
            'description' => "changed assignee to $newLeadName"
        ]);
    }

    if ($task->wasChanged(['title', 'description'])) {
        $task->activities()->create([
            'user_id'     => auth()->id(),
            'type'        => 'updated',
            'action'      => 'updated',
            'description' => 'updated task content'
        ]);
    }

    // NEW: Log if the status (Column) was changed
    if ($task->wasChanged('board_column_id')) {
        $columnName = $task->column ? $task->column->title : 'another stage';
        $task->activities()->create([
            'user_id'     => auth()->id(),
            'type'        => 'moved', // Use 'moved' type for the icon
            'action'      => 'column_change',
            'description' => "moved task to $columnName"
        ]);
    }

    // Return JSON if requested via AJAX, otherwise redirect
    if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
        return response()->json(['success' => true, 'message' => 'Task updated!']);
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
     * Toggle completion status.
     */
    public function toggleComplete(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);

        $status = $task->is_completed ? 'completed' : 'reopened';

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => $status,
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

    /**
     * Return task data as JSON for the detail modal.
     */
public function storeComment(Request $request, Task $task)
{
    $request->validate(['comment' => 'required|string|max:1000']);

    $task->activities()->create([
        'user_id'     => auth()->id(),
        'action'      => 'comment',  // ← must match JS filter
        'description' => $request->comment,
    ]);

    return response()->json(['success' => true]);
}

public function detail(Task $task)
{
    $task->load([
        'checklistItems',
        'members',
        'activities.user',
        'assignee',
        'column'
    ]);

    return response()->json([
        'id'              => $task->id,
        'title'           => $task->title,
        'description'     => $task->description,
        'priority'        => $task->priority,
        'due_date'        => $task->due_date,
        'start_date'      => $task->start_date,
        'assigned_to'     => $task->assigned_to,
        'board_column_id' => $task->board_column_id,
        'is_completed'    => $task->is_completed,
        'column'          => $task->column
                                ? ['id' => $task->column->id, 'title' => $task->column->title]
                                : null,
        'assignee'        => $task->assignee
                                ? ['id' => $task->assignee->id, 'name' => $task->assignee->name]
                                : null,
        'collaborators'   => $task->members->map(fn($u) => [
                                'id'   => $u->id,
                                'name' => $u->name,
                             ]),
        'checklist_items' => $task->checklistItems->map(fn($i) => [
                                'id'           => $i->id,
                                'title'        => $i->title,
                                'is_completed' => $i->is_completed,
                             ]),
        'activities'      => $task->activities->map(fn($a) => [
                                'id'          => $a->id,
                                'description' => $a->description,
                                'action'      => $a->action,
                                'created_at'  => $a->created_at,
                                'user'        => $a->user
                                                    ? ['id' => $a->user->id, 'name' => $a->user->name]
                                                    : null,
                             ]),
    ]);
}
}