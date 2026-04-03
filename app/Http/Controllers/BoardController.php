<?php

namespace App\Http\Controllers;

use App\Models\BoardColumn;
use App\Models\ChecklistItem;
use App\Models\Task;
use App\Http\Requests\StoreColumnRequest;
use App\Http\Requests\UpdateColumnRequest;
use App\Services\BoardService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    protected BoardService $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * Display the Kanban Board (Tasks Page)
     */
    public function index()
    {
        $data = $this->boardService->getKanbanBoardData(auth()->user());

        return view('tasks', $data);
    }

    /**
     * Display the Dashboard
     */
    public function dashboard()
    {
        $data = $this->boardService->getDashboardData(auth()->user());

        return view('dashboard', $data);
    }

    /**
     * Create a New Column
     */
    public function storeColumn(StoreColumnRequest $request)
    {
        $this->boardService->storeColumn($request->validated());

        return redirect()->back();
    }

    /**
     * Update a Column
     */
    public function updateColumn(UpdateColumnRequest $request, BoardColumn $column)
    {
        $this->boardService->updateColumn($column, $request->validated());
        
        return redirect()->back();
    }

    /**
     * Reorder Columns (Move Left/Right)
     */
    public function moveColumn(Request $request, BoardColumn $column)
    {
        $request->validate(['direction' => 'required|in:left,right']);

        $this->boardService->moveColumn($column, $request->direction);

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json(['success' => true]);
        }

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
        $request->validate(['title' => 'required|string|max:255']);

        $item = $this->boardService->storeChecklistItem($task, $request->only('title'), auth()->id());

        return response()->json($item);
    }

    /**
     * Toggle a Checklist Item
     */
    public function toggleChecklistItem(ChecklistItem $item)
    {
        $this->boardService->toggleChecklistItem($item, auth()->id());

        return response()->json(['success' => true]);
    }

    /**
     * Delete a Checklist Item
     */
    public function destroyChecklistItem(ChecklistItem $item)
    {
        $this->boardService->destroyChecklistItem($item, auth()->id());

        return response()->json(['success' => true]);
    }

    /**
     * Toggle a Member on a Task
     */
    public function toggleMember(Request $request, Task $task)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $this->boardService->toggleTaskMember($task, $request->user_id, auth()->id());

        return response()->json(['success' => true]);
    }
}