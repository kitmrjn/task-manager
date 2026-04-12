<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BoardColumn;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

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
        $this->taskService->createTask($request->validated(), auth()->id());

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();

        // Ensure is_completed carries over correctly depending on the payload
        if ($request->has('is_completed')) {
            $data['is_completed'] = $request->boolean('is_completed');
        }

        $this->taskService->updateTask($task, $data, auth()->id());

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

        $column = BoardColumn::find($request->board_column_id);

        $data = ['board_column_id' => $request->board_column_id];

        if ($column && $column->title === 'Done') {
            $data['completed_at'] = $task->completed_at ?? now();
            $data['is_completed'] = true;
        } else {
            $data['completed_at'] = null;
            $data['is_completed'] = false;
        }

        $task->update($data);

        return response()->json(['success' => true]);
    }

    public function toggleComplete(Task $task)
    {
        $this->taskService->toggleCompletion($task, auth()->id());
        
        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted!');
    }
}