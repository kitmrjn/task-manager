<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    /**
     * Handle the creation of a new task and log the activity.
     */
public function createTask(array $data, int $userId): Task
{
    $highestOrder = Task::where('board_column_id', $data['board_column_id'])->max('order');

    $task = Task::create([
        'title'           => $data['title'],
        'description'     => $data['description'] ?? null,
        'assigned_to'     => $data['assigned_to'] ?: null,
        'priority'        => $data['priority'],
        'due_date'        => $data['due_date'] ?? null,
        'start_date'      => $data['start_date'] ?? null,
        'board_column_id' => $data['board_column_id'],
        'order'           => ($highestOrder ?? 0) + 1,
        'is_completed'    => false,
        'completed_at'    => null,
    ]);

    $task->activities()->create([
        'user_id'     => $userId,
        'action'      => 'created',
        'description' => $task->assigned_to && $task->assigned_to !== $userId
            ? 'assigned you this task'
            : 'created this task',
    ]);

    return $task;
}

    /**
     * Handle updating a task, syncing collaborators, and logging changes.
     */
    public function updateTask(Task $task, array $data, int $userId): Task
    {
        $oldPriority = $task->priority;
        $oldLeadId   = $task->assigned_to;
        $oldColumnId = $task->board_column_id;

        $task->update([
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'assigned_to'     => $data['assigned_to'] ?: null,
            'priority'        => $data['priority'],
            'due_date'        => $data['due_date'] ?? null,
            'start_date'      => $data['start_date'] ?? null,
            'board_column_id' => $data['board_column_id'] ?? $task->board_column_id,
            'is_completed'    => array_key_exists('is_completed', $data) ? (bool) $data['is_completed'] : $task->is_completed,
        ]);

        // Sync collaborators
        if (!empty($data['collaborators'])) {
            $ids = json_decode($data['collaborators'], true);
            if (is_array($ids)) {
                $oldMemberIds = $task->members()->pluck('users.id')->toArray();
                $task->members()->sync($ids);

                // Log activity for newly added collaborators only
                $newlyAdded = array_diff($ids, $oldMemberIds);
                foreach ($newlyAdded as $newUserId) {
                    if ($newUserId == $userId) continue;

                    $task->activities()->create([
                        'user_id'     => $userId,
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
                'user_id'     => $userId,
                'action'      => 'priority_change',
                'description' => 'changed priority to ' . strtoupper($task->priority),
            ]);
        }

        if ($oldColumnId != $task->board_column_id) {
            $colName = $task->column->title ?? 'another stage';
            $task->activities()->create([
                'user_id'     => $userId,
                'action'      => 'column_change',
                'description' => "moved to $colName",
            ]);
        }
        
        if ($oldLeadId != $task->assigned_to && $task->assigned_to) {
            $task->activities()->create([
                'user_id'     => $userId,
                'action'      => 'lead_change',
                'description' => 'assigned you this task',
            ]);
        }

        return $task;
    }

    /**
     * Toggle the completion status of a task and log the activity.
     */
    public function toggleCompletion(Task $task, int $userId): Task
    {
        $isNowComplete = !$task->is_completed;

        $task->update([
            'is_completed' => $isNowComplete,
            'completed_at' => $isNowComplete ? now() : null,
        ]);

        $status = $isNowComplete ? 'completed' : 'reopened';

        $task->activities()->create([
            'user_id'     => $userId,
            'action'      => $status,
            'description' => "marked this task as $status",
        ]);

        return $task;
    }
}