<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Models\Task;
use App\Models\BoardColumn; // Added this import
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display the Kanban Board (Tasks Page)
     */
    public function index()
    {
        // Fetch the first board with its columns (ordered) and tasks
        $board = Board::with(['columns' => function($query) {
            $query->orderBy('order', 'asc');
        }, 'columns.tasks.assignee'])->first();

        $users = User::all();

        return view('tasks', compact('board', 'users'));
    }

    /**
     * Display the Stats Overview (Dashboard Page)
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'total' => Task::count(),
            'my_tasks' => Task::where('assigned_to', $user->id)->count(),
            'high_priority' => Task::where('priority', 'high')->count(),
        ];

        $myTasks = Task::where('assigned_to', $user->id)
                    ->with('column')
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
    
}