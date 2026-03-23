<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Models\Task;
use App\Models\BoardColumn; // Added this import
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display the Kanban Board (Tasks Page)
     */
    public function index()
{
    // We add 'columns.tasks.checklistItems' so each task knows about its subtasks
    $board = Board::with([
        'columns' => function($query) {
            $query->orderBy('order', 'asc');
        }, 
        'columns.tasks.assignee', 
        'columns.tasks.checklistItems', // <--- ADD THIS LINE
        'columns.tasks.members',
        'columns.tasks.attachments'

    ])->first();

    $users = User::all();

    return view('tasks', compact('board', 'users'));
}

    /**
     * Display the Stats Overview (Dashboard Page)
     */
public function dashboard()
{
    $user = auth()->user();
    
    // 1. Get tasks where user is the LEAD or a COLLABORATOR
    $myTasksQuery = Task::where('assigned_to', $user->id)
        ->orWhereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        });

    $stats = [
        'total' => Task::count(),
        'my_tasks' => $myTasksQuery->count(), // Updated count
        'high_priority' => Task::where('priority', 'high')->count(),
    ];

    $myTasks = $myTasksQuery->with('column')
                ->latest()
                ->take(5)
                ->get();

    return view('dashboard', compact('stats', 'myTasks'));
}

    /**
     * Create a New Column
     */
    public function storeColumn(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'color' => 'required|string',
        'description' => 'nullable|string',
        'board_id' => 'required|exists:boards,id'
    ]);

    $highestOrder = BoardColumn::where('board_id', $request->board_id)->max('order');

    BoardColumn::create([
        'title' => $request->title,
        'color' => $request->color,
        'description' => $request->description,
        'board_id' => $request->board_id,
        'order' => ($highestOrder ?? 0) + 1
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
        $boardId = $column->board_id;

        if ($request->direction === 'left') {
            $swapColumn = BoardColumn::where('board_id', $boardId)
                ->where('order', '<', $currentOrder)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $swapColumn = BoardColumn::where('board_id', $boardId)
                ->where('order', '>', $currentOrder)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($swapColumn) {
            $column->update(['order' => $swapColumn->order]);
            $swapColumn->update(['order' => $currentOrder]);
        }

        return redirect()->back();
    }
    
public function updateColumn(Request $request, BoardColumn $column)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string', // Ensure this is here!
        'color' => 'required|string',
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
        //Storing Checkilsit Item
public function storeChecklistItem(Request $request, Task $task)
{
    $item = $task->checklistItems()->create([
        'title' => $request->title,
        'is_completed' => false,
    ]);

    // Log the subtask creation
    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'checklist_added',
        'description' => "added subtask: {$item->title}"
    ]);

    return response()->json($item);
}
    
public function toggleChecklistItem(ChecklistItem $item)
{
    $item->update(['is_completed' => !$item->is_completed]);
    $status = $item->is_completed ? 'completed' : 'incomplete';

    $item->task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'checklist_toggle',
        'description' => "marked \"{$item->title}\" as $status"
    ]);

    return response()->json(['success' => true]);
}
public function toggleMember(Request $request, Task $task)
{
    $task->members()->toggle($request->user_id);
    $user = \App\Models\User::find($request->user_id);
    
    // Check if the user was added or removed
    $isAttached = $task->members()->where('user_id', $request->user_id)->exists();
    $actionText = $isAttached ? "added {$user->name} as collaborator" : "removed {$user->name} from collaborators";

    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'member_toggle',
        'description' => $actionText
    ]);

    return response()->json(['success' => true]);
}
public function destroyChecklistItem(ChecklistItem $item)
{
    $task = $item->task; // Get task before deleting item
    $title = $item->title;
    $item->delete();

    $task->activities()->create([
        'user_id' => auth()->id(),
        'action' => 'checklist_deleted',
        'description' => "deleted subtask: \"$title\""
    ]);

    return response()->json(['success' => true]);
}

}