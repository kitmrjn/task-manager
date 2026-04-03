<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Task;
use App\Models\BoardColumn;
use App\Models\ChecklistItem;
use App\Models\User;
use App\Models\TaskActivity;
use Illuminate\Support\Facades\DB;

class BoardService
{
    /**
     * Get the Kanban board and filter tasks based on user role.
     */
    public function getKanbanBoardData(User $user): array
    {
        $board = Board::with([
            'columns' => fn($q) => $q->orderBy('order', 'asc'),
        ])->first();

        if ($board) {
            foreach ($board->columns as $column) {
                $query = $column->tasks()
                    ->with(['assignee', 'checklistItems', 'members', 'attachments']);

                if ($user->role !== 'admin') {
                    $query->where(function ($q) use ($user) {
                        $q->where('assigned_to', $user->id)
                          ->orWhere('creator_id', $user->id)
                          ->orWhereHas('members', fn($q2) => $q2->where('users.id', $user->id));
                    });
                }

                $column->setRelation('tasks', $query->orderBy('order')->get());
            }
        }

        return [
            'board' => $board,
            'users' => User::all()
        ];
    }

    /**
     * Get aggregated data for the user dashboard.
     */
    public function getDashboardData(User $user): array
    {
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

        $recentActivity = TaskActivity::with('user')
            ->latest()
            ->take(8)
            ->get();

        return compact('stats', 'myTasks', 'recentActivity');
    }

    public function storeColumn(array $data): BoardColumn
    {
        $highestOrder = BoardColumn::where('board_id', $data['board_id'])->max('order');

        return BoardColumn::create([
            'title'       => $data['title'],
            'color'       => $data['color'],
            'description' => $data['description'] ?? null,
            'board_id'    => $data['board_id'],
            'order'       => ($highestOrder ?? 0) + 1,
        ]);
    }

    public function updateColumn(BoardColumn $column, array $data): BoardColumn
    {
        $column->update($data);
        return $column;
    }

    public function moveColumn(BoardColumn $column, string $direction): void
    {
        $currentOrder = $column->order;
        $boardId      = $column->board_id;

        $swapColumn = $direction === 'left'
            ? BoardColumn::where('board_id', $boardId)->where('order', '<', $currentOrder)->orderBy('order', 'desc')->first()
            : BoardColumn::where('board_id', $boardId)->where('order', '>', $currentOrder)->orderBy('order', 'asc')->first();

        if ($swapColumn) {
            $column->update(['order' => $swapColumn->order]);
            $swapColumn->update(['order' => $currentOrder]);
        }
    }

    public function storeChecklistItem(Task $task, array $data, int $userId): ChecklistItem
    {
        $item = $task->checklistItems()->create([
            'title'        => $data['title'],
            'is_completed' => false,
        ]);

        $task->activities()->create([
            'user_id'     => $userId,
            'action'      => 'checklist_added',
            'description' => "added subtask: {$item->title}",
        ]);

        return $item;
    }

    public function toggleChecklistItem(ChecklistItem $item, int $userId): void
    {
        $item->update(['is_completed' => !$item->is_completed]);
        $status = $item->is_completed ? 'completed' : 'incomplete';

        $item->task->activities()->create([
            'user_id'     => $userId,
            'action'      => 'checklist_toggle',
            'description' => "marked \"{$item->title}\" as $status",
        ]);
    }

    public function destroyChecklistItem(ChecklistItem $item, int $userId): void
    {
        $task  = $item->task;
        $title = $item->title;
        $item->delete();

        $task->activities()->create([
            'user_id'     => $userId,
            'action'      => 'checklist_deleted',
            'description' => "deleted subtask: \"$title\"",
        ]);
    }

    public function toggleTaskMember(Task $task, int $memberId, int $userId): void
    {
        $task->members()->toggle($memberId);
        $memberUser = User::find($memberId);
        
        if ($memberUser) {
            $isAttached = $task->members()->where('user_id', $memberId)->exists();

            $task->activities()->create([
                'user_id'     => $userId,
                'action'      => 'member_toggle',
                'description' => $isAttached
                    ? "added {$memberUser->name} as collaborator"
                    : "removed {$memberUser->name} from collaborators",
            ]);
        }
    }
}