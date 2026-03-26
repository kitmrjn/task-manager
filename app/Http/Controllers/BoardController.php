<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Models\Task;
use App\Models\BoardColumn;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    /**
     * Display the Kanban Board (Tasks Page)
     *
     * admin       → sees ALL tasks
     * team_member → sees tasks where they are:
     *               1. the assignee (lead)
     *               2. a collaborator (task_user pivot)
     *               3. the creator
     */
    public function index()
    {
        $user  = auth()->user();
        $board = Board::with([
            'columns' => fn($q) => $q->orderBy('order', 'asc'),
        ])->first();

        if ($board) {
            foreach ($board->columns as $column) {
                $query = $column->tasks()
                    ->with(['assignee', 'checklistItems', 'members', 'attachments']);

                if ($user->role !== 'admin') {
                    $query->where(function ($q) use ($user) {
                        // Lead / assignee
                        $q->where('assigned_to', $user->id)
                          // Creator of the task
                          ->orWhere('creator_id', $user->id)
                          // Collaborator via pivot
                          ->orWhereHas('members', fn($q2) => $q2->where('users.id', $user->id));
                    });
                }

                $column->setRelation('tasks', $query->orderBy('order')->get());
            }
        }

        $users = User::all();

        return view('tasks', compact('board', 'users'));
    }

    /**
     * Display the Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        $assignedIds      = Task::where('assigned_to', $user->id)->pluck('id');
        $createdIds       = Task::where('creator_id', $user->id)->pluck('id');
        $collaboratingIds = DB::table('task_user')
                                ->where('user_id', $user->id)
                                ->pluck('task_id');

        $myTaskIds = $assignedIds
            ->merge($createdIds)
            ->merge($collaboratingIds)
            ->unique()
            ->values();

        $stats = [
            'total'         => Task::count(),
            'my_tasks'      => $myTaskIds->count(),
            'completed'     => Task::where('is_completed', true)->count(),
            'high_priority' => Task::where('priority', 'high')->count(),
        ];

        $myTasks = Task::whereIn('id', $myTaskIds)
            ->with('column')
            ->latest()
            ->take(5)
            ->get();

        $recentActivity = \App\Models\TaskActivity::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'myTasks', 'recentActivity'));
    }

    /**
     * Create a New Column
     */
    public function storeColumn(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'color'       => 'required|string|in:gray,blue,green,yellow,red,orange,purple,pink,teal,indigo',
            'description' => 'nullable|string',
            'board_id'    => 'required|exists:boards,id',
        ]);

        $highestOrder = BoardColumn::where('board_id', $request->board_id)->max('order');

        BoardColumn::create([
            'title'       => $request->title,
            'color'       => $request->color,
            'description' => $request->description,
            'board_id'    => $request->board_id,
            'order'       => ($highestOrder ?? 0) + 1,
        ]);

        return redirect()->back();
    }

    /**
     * Reorder Columns (Move Left/Right)
     */
    public function moveColumn(Request $request, BoardColumn $column)
    {
        $request->validate(['direction' => 'required|in:left,right']);

        $currentOrder = $column->order;
        $boardId      = $column->board_id;

        $swapColumn = $request->direction === 'left'
            ? BoardColumn::where('board_id', $boardId)->where('order', '<', $currentOrder)->orderBy('order', 'desc')->first()
            : BoardColumn::where('board_id', $boardId)->where('order', '>', $currentOrder)->orderBy('order', 'asc')->first();

        if ($swapColumn) {
            $column->update(['order' => $swapColumn->order]);
            $swapColumn->update(['order' => $currentOrder]);
        }

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Update a Column
     */
    public function updateColumn(Request $request, BoardColumn $column)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|in:gray,blue,green,yellow,red,orange,purple,pink,teal,indigo',
        ]);

        $column->update($validated);
        return redirect()->back();
    }

    /**
     * Remove a Column
     */
    public function destroyColumn(BoardColumn $column)
    {
        $column->delete();
        return redirect()->back();
    }

    /**
     * Store a Checklist Item
     */
    public function storeChecklistItem(Request $request, Task $task)
    {
        $item = $task->checklistItems()->create([
            'title'        => $request->title,
            'is_completed' => false,
        ]);

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'checklist_added',
            'description' => "added subtask: {$item->title}",
        ]);

        return response()->json($item);
    }

    /**
     * Toggle a Checklist Item
     */
    public function toggleChecklistItem(ChecklistItem $item)
    {
        $item->update(['is_completed' => !$item->is_completed]);
        $status = $item->is_completed ? 'completed' : 'incomplete';

        $item->task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'checklist_toggle',
            'description' => "marked \"{$item->title}\" as $status",
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Toggle a Member on a Task
     */
    public function toggleMember(Request $request, Task $task)
    {
        $task->members()->toggle($request->user_id);
        $memberUser = User::find($request->user_id);
        $isAttached = $task->members()->where('user_id', $request->user_id)->exists();

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'member_toggle',
            'description' => $isAttached
                ? "added {$memberUser->name} as collaborator"
                : "removed {$memberUser->name} from collaborators",
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a Checklist Item
     */
    public function destroyChecklistItem(ChecklistItem $item)
    {
        $task  = $item->task;
        $title = $item->title;
        $item->delete();

        $task->activities()->create([
            'user_id'     => auth()->id(),
            'action'      => 'checklist_deleted',
            'description' => "deleted subtask: \"$title\"",
        ]);

        return response()->json(['success' => true]);
    }
}