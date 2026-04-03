<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Models\Campaign;
use App\Services\AdminUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected AdminUserService $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') abort(403, 'Unauthorized.');

        $query = User::with(['campaign', 'teamLeader']);

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Campaign Filter
        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        // 4. Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereNotNull('email_verified_at');
            } elseif ($request->status === 'pending') {
                $query->where('is_active', true)->whereNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $campaigns = Campaign::orderBy('name')->get();
        $teamLeaders = User::where('role', '!=', 'super_admin')->orderBy('name')->get();
        $roles = \App\Models\Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'campaigns', 'teamLeaders'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->adminUserService->createUser($request->validated());
        return redirect()->back()->with('success', 'User created successfully. Setup email dispatched.');
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'           => 'required|string|exists:roles,slug',
            'campaign_id'    => 'nullable|exists:campaigns,id',
            'team_leader_id' => 'nullable|exists:users,id',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', "{$user->name}'s profile updated successfully.");
    }

    public function toggleStatus(User $user)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "User account has been $status.");
    }

    public function resendInvite(User $user)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
        $user->notify(new \App\Notifications\WelcomeSetPasswordNotification($token));

        return redirect()->back()->with('success', "Setup invitation resent to {$user->email}.");
    }
}