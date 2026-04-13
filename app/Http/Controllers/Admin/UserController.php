<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Models\Campaign;
use App\Models\UserValidId;
use App\Services\AdminUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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

        $query = User::with(['campaign', 'teamLeader', 'validIds']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role'))     $query->where('role', $request->role);
        if ($request->filled('campaign')) $query->where('campaign_id', $request->campaign);

        if ($request->filled('status')) {
            if ($request->status === 'active')   $query->where('is_active', true)->whereNotNull('email_verified_at');
            elseif ($request->status === 'pending')  $query->where('is_active', true)->whereNull('email_verified_at');
            elseif ($request->status === 'inactive') $query->where('is_active', false);
        }

        $users       = $query->latest()->paginate(15)->withQueryString();
        $campaigns   = Campaign::orderBy('name')->get();
        $teamLeaders = User::where('role', '!=', 'super_admin')->orderBy('name')->get();
        $roles       = \App\Models\Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'campaigns', 'teamLeaders', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // Handle profile photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('user-photos', 'public');
        }

        $user = $this->adminUserService->createUser($data);

        // Handle valid ID uploads
        $this->handleValidIdUploads($request, $user);

        return redirect()->back()->with('success', 'User created successfully. Setup email dispatched.');
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'              => 'required|string|exists:roles,slug',
            'campaign_id'       => 'nullable|exists:campaigns,id',
            'team_leader_id'    => 'nullable|exists:users,id',
            'phone'             => 'nullable|string|max:20',
            'city'              => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:255',
            'country'           => 'nullable|string|max:100',
            'sss_number'        => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'tin_number'        => 'nullable|string|max:50',
            'pag_ibig_number'   => 'nullable|string|max:50',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile photo update
        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $validated['photo'] = $request->file('photo')->store('user-photos', 'public');
        }

        $user->update($validated);

        // Handle valid ID uploads
        $this->handleValidIdUploads($request, $user);

        return redirect()->back()->with('success', "{$user->name}'s profile updated successfully.");
    }

    /**
     * Handle uploading valid ID files for a user.
     * Each ID type can only be attached once per user.
     */
    private function handleValidIdUploads(Request $request, User $user): void
    {
        $idTypes = ['sss_card', 'philhealth_card', 'tin_card', 'pagibig_card', 'passport', 'drivers_license'];

        foreach ($idTypes as $type) {
            $fileKey = "valid_id_{$type}";
            if (!$request->hasFile($fileKey)) continue;

            $file = $request->file($fileKey);
            if (!$file->isValid()) continue;

            // Delete old file if replacing
            $existing = $user->validIds()->where('id_type', $type)->first();
            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }

            $path = $file->store("valid-ids/{$user->id}", 'public');

            $user->validIds()->create([
                'id_type'           => $type,
                'file_path'         => $path,
                'original_filename' => $file->getClientOriginalName(),
            ]);
        }
    }

    public function deleteValidId(Request $request, User $user)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);

        $validId = UserValidId::where('user_id', $user->id)
                              ->where('id_type', $request->id_type)
                              ->firstOrFail();

        Storage::disk('public')->delete($validId->file_path);
        $validId->delete();

        return redirect()->back()->with('success', 'ID removed successfully.');
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