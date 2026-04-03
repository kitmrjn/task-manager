<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function index()
    {
        $members     = User::withCount('tasks')->with('permissions')->get();
        $teamCount   = $members->count();
        $openTasks   = Task::whereDoesntHave('column', fn($q) => $q->where('title', 'Done'))->count();
        $activeCount = $members->filter(
            fn($m) => $m->last_active && Carbon::parse($m->last_active)->diffInMinutes() < 30
        )->count();

        return view('team', compact('members', 'teamCount', 'openTasks', 'activeCount'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Must be an Admin or Super Admin
        if (!auth()->user()->isAtLeastAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 2. Prevent modifying someone with a higher rank (Admin modifying Super Admin)
        if (auth()->user()->roleLevel() < $user->roleLevel()) {
            return response()->json(['error' => 'You cannot modify a user with a higher role level.'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:super_admin,admin,manager,team_member',
            'password' => 'nullable|string|min:8',
        ]);

        // 3. Prevent promoting someone above your own rank
        $newRoleLevel = match($validated['role']) {
            'super_admin' => 4,
            'admin' => 3,
            'manager', 'team_leader' => 2,
            default => 1,
        };

        if (auth()->user()->roleLevel() < $newRoleLevel) {
            return response()->json(['error' => 'You cannot grant a role higher than your own.'], 403);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAtLeastAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (auth()->user()->roleLevel() < $user->roleLevel()) {
            return response()->json(['error' => 'You cannot delete a user with a higher role level.'], 403);
        }

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function updatePermissions(Request $request, User $user)
    {
        if (!auth()->user()->isAtLeastAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (auth()->user()->roleLevel() < $user->roleLevel()) {
            return response()->json(['error' => 'You cannot modify permissions of a higher-ranking user.'], 403);
        }

        $validated = $request->validate([
            'can_view_calendar'  => 'boolean',
            'can_view_analytics' => 'boolean',
            'can_view_team'      => 'boolean',
            'can_view_reports'   => 'boolean',
            'can_create_tasks'   => 'boolean',
            'can_delete_tasks'   => 'boolean',
            'can_edit_tasks'     => 'boolean',
            'can_add_column'     => 'boolean', 
        ]);

        $user->permissions()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json(['success' => true]);
    }
}