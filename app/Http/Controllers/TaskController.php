<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BoardColumn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display the task board.
     */
    public function index(Request $request)
    {
        // Adjust this query to match how you fetch your board/columns
        $board = \App\Models\Board::with(['columns.tasks.assignee', 'columns.tasks.checklistItems', 'columns.tasks.members'])
                    ->first(); // Or find($id)
        
        $users = \App\Models\User::all();

        // THIS IS THE SYNC TRIGGER:
        // If the request comes from our JS sync (AJAX), return a specific partial view
        if ($request->ajax()) {
            return view('tasks.partials.board_container', compact('board', 'users'));
        }

        return view('tasks.index', compact('board', 'users'));
    }
    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'board_column_id' => 'required|exists:board_columns,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'assigned_to'     => 'nullable|exists:users,id',
            'priority'        => 'required|in:low,medium,high',
            'due_date'        => 'nullable|date',
            'start_date'      => 'nullable|date',
        ]);

        $highestOrder = Task::where('board_column_id', $request->board_column_id)->max('order');

        $task = Task::create([
            'board_column_id' => $request->board_column_id,
            'title'           => $request->title,
            'description'     => $request->description,
            'assigned_to'     => $request->assigned_to,
            'priority'        => $request->priority,
            'due_date'        => $request->due_date,
            'start_date'      => $request->start_date,
            'order'           => ($highestOrder ?? 0) + 1,
            'is_completed'    => false,
        ]);

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'description' => 'created this task',
        ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update the specified task in storage.
     * Returns JSON so the detail modal JS can handle the response.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'assigned_to'     => 'nullable',
            'priority'        => 'required|in:low,medium,high',
            'due_date'        => 'nullable|date',
            'start_date'      => 'nullable|date',
            'is_completed'    => 'nullable|boolean',
            'collaborators'   => 'nullable|string',
            'board_column_id' => 'nullable|exists:board_columns,id',
        ]);

        $oldPriority = $task->priority;
        $oldLeadId   = $task->assigned_to;
        $oldColumnId = $task->board_column_id;

        $task->update([
            'title'           => $request->title,
            'description'     => $request->description,
            'assigned_to'     => $request->assigned_to ?: null,
            'priority'        => $request->priority,
            'due_date'        => $request->due_date,
            'start_date'      => $request->start_date,
            'board_column_id' => $request->board_column_id ?? $task->board_column_id,
            'is_completed'    => $request->has('is_completed')
                                    ? (bool) $request->is_completed
                                    : $task->is_completed,
        ]);

        // Sync collaborators
        if ($request->filled('collaborators')) {
            $ids = json_decode($request->collaborators, true);
            if (is_array($ids)) {
                $task->members()->sync($ids);
            }
        } else {
            $task->members()->sync([]);
        }

        // Activity logs
        if ($oldPriority !== $task->priority) {
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'priority_change',
                'description' => 'changed priority to ' . strtoupper($task->priority),
            ]);
        }

        if ($task->wasChanged('start_date')) {
            $startDateText = $task->start_date
                ? \Carbon\Carbon::parse($task->start_date)->format('M d, Y')
                : 'Removed';
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'start_date_change',
                'description' => "set the start date to $startDateText",
            ]);
        }

        if ($oldLeadId != $task->assigned_to) {
            $newLeadName = $task->assignee ? $task->assignee->name : 'Unassigned';
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'lead_change',
                'description' => "changed lead assignee to $newLeadName",
            ]);
        }

        if ($oldColumnId != $task->board_column_id) {
            $colName = $task->column->title ?? 'Unknown';
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'column_change',
                'description' => "moved to $colName",
            ]);
        }

        if ($task->wasChanged(['title', 'description', 'due_date'])) {
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'updated',
                'description' => 'updated task content',
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Post a comment on a task.
     * Called by POST /tasks/{task}/comments from tasks.js postComment()
     */
    public function storeComment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'comment',
            'description' => $request->comment,
        ]);

        return response()->json(['success' => true]);
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
            'board_column_id' => 'required|exists:board_columns,id',
        ]);

        $task->update(['board_column_id' => $request->board_column_id]);

        return response()->json(['success' => true, 'message' => 'Task moved successfully!']);
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
            'description' => "marked this task as $status",
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
<<<<<<< HEAD
    public function detail(Task $task)
    {
=======
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
>>>>>>> main
    $task->load([
        'checklistItems',
        'members',
        'activities.user',
        'assignee',
<<<<<<< HEAD
        'column',
        'attachments.uploader', // ← ADD THIS
    ]);
 
    $data = $task->toArray();
 
    // Add formatted attachment data for the JS
    $data['attachments'] = $task->attachments->map(fn($a) => [
        'id'            => $a->id,
        'original_name' => $a->original_name,
        'mime_type'     => $a->mime_type,
        'size'          => $a->humanSize(),
        'url'           => $a->url(),
        'is_image'      => $a->isImage(),
        'uploader'      => $a->uploader?->name,
        'created_at'    => $a->created_at,
    ]);
 
    $data['subtasks'] = [];
 
    return response()->json($data);
    }
=======
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
>>>>>>> main
}