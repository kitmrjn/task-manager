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
        $members     = User::withCount('tasks')->get();
        $teamCount   = $members->count();
        $openTasks   = Task::whereDoesntHave('column', fn($q) => $q->where('title', 'Done'))->count();
        $activeCount = $members->filter(
            fn($m) => $m->last_active && Carbon::parse($m->last_active)->diffInMinutes() < 30
        )->count();

        return view('team', compact('members', 'teamCount', 'openTasks', 'activeCount'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,manager,team_member', // ← only these 3 are valid
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
}