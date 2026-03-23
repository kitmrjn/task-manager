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

    $assignedIds      = Task::where('assigned_to', $user->id)->pluck('id');
    $collaboratingIds = \DB::table('task_user')
                            ->where('user_id', $user->id)
                            ->pluck('task_id');

    $myTaskIds = $assignedIds->merge($collaboratingIds)->unique()->values();

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
        'board_id'    => 'required|exists:boards,id'
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
    $boardId      = $column->board_id;

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
        // Swap the order values
        $column->update(['order' => $swapColumn->order]);
        $swapColumn->update(['order' => $currentOrder]);
    }

    // Return JSON since we're calling via fetch now
    if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
        return response()->json(['success' => true]);
    }

    return redirect()->back();
}
    
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
    
    $isAttached = $task->members()->where('user_id', $request->user_id)->exists();
    
    // Log for the activity feed
    $task->activities()->create([
        'user_id' => auth()->id(),
        'type' => 'assigned', // This matches your $iconMap in Blade
        'description' => $isAttached 
            ? "added {$user->name} to \"{$task->title}\"" 
            : "removed {$user->name} from \"{$task->title}\""
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