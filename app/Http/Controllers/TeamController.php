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

    /**
     * Update member name / email / role / password
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,manager,team_member',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Update a member's feature permissions (toggle on/off)
     * PATCH /team/members/{user}/permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'can_view_calendar'  => 'boolean',
            'can_view_analytics' => 'boolean',
            'can_view_team'      => 'boolean',
            'can_view_reports'   => 'boolean',
            'can_create_tasks'   => 'boolean',
            'can_delete_tasks'   => 'boolean',
        ]);

        // updateOrCreate so it works even if no permissions row exists yet
        $user->permissions()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json(['success' => true]);
    }
}