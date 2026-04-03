<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BoardColumn;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display the task board.
     */
    public function index(Request $request)
    {
        $board = \App\Models\Board::with(['columns.tasks.assignee', 'columns.tasks.checklistItems', 'columns.tasks.members'])
                    ->first(); 
        
        $users = \App\Models\User::all();

        if ($request->ajax()) {
            return view('tasks.partials.board_container', compact('board', 'users'));
        }

        return view('tasks.index', compact('board', 'users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $highestOrder = Task::where('board_column_id', $validated['board_column_id'])->max('order');

        $task = Task::create([
            'board_column_id' => $validated['board_column_id'],
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'assigned_to'     => $validated['assigned_to'],
            'creator_id'      => auth()->id(),
            'priority'        => $validated['priority'],
            'due_date'        => $validated['due_date'],
            'start_date'      => $validated['start_date'],
            'order'           => ($highestOrder ?? 0) + 1,
            'is_completed'    => false,
        ]);

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'description' => $task->assigned_to && $task->assigned_to !== auth()->id()
                ? 'assigned you this task'
                : 'created this task',
        ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $oldPriority = $task->priority;
        $oldLeadId   = $task->assigned_to;
        $oldColumnId = $task->board_column_id;

        $task->update([
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'assigned_to'     => $validated['assigned_to'] ?: null,
            'priority'        => $validated['priority'],
            'due_date'        => $validated['due_date'],
            'start_date'      => $validated['start_date'],
            'board_column_id' => $validated['board_column_id'] ?? $task->board_column_id,
            'is_completed'    => $request->has('is_completed') ? (bool) $validated['is_completed'] : $task->is_completed,
        ]);

        // Sync collaborators
        if ($request->filled('collaborators')) {
            $ids = json_decode($validated['collaborators'], true);
            if (is_array($ids)) {
                $oldMemberIds = $task->members()->pluck('users.id')->toArray();
                $task->members()->sync($ids);

                // Log activity for newly added collaborators only
                $newlyAdded = array_diff($ids, $oldMemberIds);
                foreach ($newlyAdded as $newUserId) {
                    // Don't notify if they added themselves
                    if ($newUserId == auth()->id()) continue;

                    $task->activities()->create([
                        'user_id'     => auth()->id(),
                        'action'      => 'lead_change',
                        'description' => 'added you as a collaborator',
                    ]);
                }
            }
        } else {
            $task->members()->sync([]);
        }

        // Activity Logs
        if ($oldPriority !== $task->priority) {
            $task->activities()->create([
                'user_id' => auth()->id(),
                'action' => 'priority_change',
                'description' => 'changed priority to ' . strtoupper($task->priority),
            ]);
        }

        if ($oldColumnId != $task->board_column_id) {
            $colName = $task->column->title ?? 'another stage';
            $task->activities()->create([
                'user_id' => auth()->id(),
                'action' => 'column_change',
                'description' => "moved to $colName",
            ]);
        }
        
        if ($oldLeadId != $task->assigned_to && $task->assigned_to) {
            $task->activities()->create([
                'user_id'     => auth()->id(),
                'action'      => 'lead_change',
                'description' => 'assigned you this task',
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Post a comment.
     */
    public function storeComment(Request $request, Task $task)
    {
        $request->validate(['comment' => 'required|string|max:2000']);

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'comment',
            'description' => $request->comment,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Return task data as JSON for the detail modal.
     */
    public function detail(Task $task)
    {
        $task->load([
            'checklistItems',
            'members',
            'activities.user',
            'assignee',
            'column',
            'attachments.uploader',
        ]);

        return response()->json([
            'id'              => $task->id,
            'title'           => $task->title,
            'description'     => $task->description,
            'priority'        => $task->priority,
            'due_date'        => $task->due_date,
            'start_date'      => $task->start_date,
            'is_completed'    => $task->is_completed,
            'column'          => $task->column ? ['id' => $task->column->id, 'title' => $task->column->title] : null,
            'assigned_to'     => $task->assigned_to,
            'assignee'        => $task->assignee ? ['id' => $task->assignee->id, 'name' => $task->assignee->name] : null,
            'collaborators'   => $task->members->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
            'checklist_items' => $task->checklistItems->map(fn($i) => ['id' => $i->id, 'title' => $i->title, 'is_completed' => $i->is_completed]),
            'can_edit'        => auth()->user()->can_access('can_edit_tasks'), 
            'attachments'     => $task->attachments->map(fn($a) => [
                'id'            => $a->id,
                'original_name' => $a->original_name,
                'mime_type'     => $a->mime_type,
                'size'          => $a->humanSize(),
                'url'           => $a->url(),
                'is_image'      => $a->isImage(),
                'uploader'      => $a->uploader?->name,
            ]),
            'activities'      => $task->activities->map(fn($a) => [
                'id'          => $a->id,
                'description' => $a->description,
                'action'      => $a->action,
                'created_at'  => $a->created_at,
                'user'        => $a->user ? ['id' => $a->user->id, 'name' => $a->user->name] : null,
            ]),
        ]);
    }

    public function move(Request $request, Task $task)
    {
        if (!auth()->user()->can_access('can_edit_tasks')) {
            return response()->json(['success' => false], 403);
        }
        $request->validate(['board_column_id' => 'required|exists:board_columns,id']);
        $task->update(['board_column_id' => $request->board_column_id]);
        return response()->json(['success' => true]);
    }

    public function toggleComplete(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        $status = $task->is_completed ? 'completed' : 'reopened';
        $task->activities()->create([
            'user_id' => auth()->id(),
            'action' => $status,
            'description' => "marked this task as $status",
        ]);
        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted!');
    }
}