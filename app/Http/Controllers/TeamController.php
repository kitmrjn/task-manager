<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;

class TeamController extends Controller
{
    public function index()
    {
        $activeUserIds = \DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        $members = User::withCount([
            'tasks as tasks_count' => fn($q) => $q->where('is_completed', false)
        ])->get();

        $teamCount   = $members->count();
        $openTasks   = Task::where('is_completed', false)->count();
        $activeCount = count($activeUserIds);

        return view('team', compact('members', 'teamCount', 'openTasks', 'activeCount', 'activeUserIds'));
    }

    /**
     * Update a member's role — admin only
     */
    public function updateRole(Request $request, User $user)
    {
        // Only admins can change roles
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Cannot change your own role
        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'You cannot change your own role'], 422);
        }

        $request->validate([
            'role' => 'required|in:admin,manager,member'
        ]);

        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => "{$user->name}'s role updated to {$request->role}"
        ]);
    }

    /**
     * Get tasks assigned to or collaborated on by a member
     */
    public function memberTasks(User $user)
    {
        $assignedTasks = Task::where('assigned_to', $user->id)
            ->where('is_completed', false)
            ->with('column')
            ->get()
            ->map(fn($t) => [
                'id'       => $t->id,
                'title'    => $t->title,
                'priority' => $t->priority,
                'due_date' => $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('M d') : null,
                'column'   => $t->column?->title,
                'type'     => 'assigned',
            ]);

        $collaboratingTasks = Task::whereHas('members', fn($q) => $q->where('users.id', $user->id))
            ->where('is_completed', false)
            ->with('column')
            ->get()
            ->map(fn($t) => [
                'id'       => $t->id,
                'title'    => $t->title,
                'priority' => $t->priority,
                'due_date' => $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('M d') : null,
                'column'   => $t->column?->title,
                'type'     => 'collaborating',
            ]);

        $completedCount = Task::where('assigned_to', $user->id)
            ->where('is_completed', true)
            ->count();

        return response()->json([
            'user'               => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role ?? 'member'],
            'assigned'           => $assignedTasks,
            'collaborating'      => $collaboratingTasks,
            'completed_count'    => $completedCount,
        ]);
    }
}