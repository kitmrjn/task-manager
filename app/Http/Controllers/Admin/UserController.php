<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Models\Campaign;
use App\Services\AdminUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected AdminUserService $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    /**
     * Display a listing of the users for the admin panel.
     */
    public function index()
    {
        // Ensure only super_admins can view this page
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized Access.');
        }

        $users = User::with(['campaign', 'teamLeader'])->latest()->get();
        $campaigns = Campaign::orderBy('name')->get();
        
        // Potential team leaders could be anyone, or restricted by role
        $teamLeaders = User::where('role', '!=', 'super_admin')->orderBy('name')->get();

        // Note: You will need to create the 'admin.users.index' blade view later
        return view('admin.users.index', compact('users', 'campaigns', 'teamLeaders'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->adminUserService->createUser($request->validated());

        return redirect()->back()->with('success', 'User created successfully. A setup email has been sent to them.');
    }
}