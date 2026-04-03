<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Role;
use Illuminate\Support\Str;

class SystemDataController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        
        $campaigns = Campaign::withCount('users')->latest()->get();
        $roles = Role::withCount('users')->orderBy('is_system', 'desc')->latest()->get();
        
        return view('admin.system-data.index', compact('campaigns', 'roles'));
    }

    public function storeCampaign(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        
        $request->validate(['name' => 'required|string|max:255|unique:campaigns']);
        Campaign::create(['name' => $request->name]);
        
        return back()->with('success', 'Campaign successfully created.');
    }

    public function destroyCampaign(Campaign $campaign)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        
        $campaign->delete(); // Users assigned to this will safely have campaign_id set to null
        return back()->with('success', 'Campaign deleted.');
    }

    public function storeRole(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        
        $request->validate(['name' => 'required|string|max:255|unique:roles']);
        
        Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '_'),
            'is_system' => false
        ]);
        
        return back()->with('success', 'Custom role created.');
    }

    public function destroyRole(Role $role)
    {
        if (auth()->user()->role !== 'super_admin') abort(403);
        if ($role->is_system) return back()->withErrors(['error' => 'You cannot delete core system roles.']);
        if ($role->users()->count() > 0) return back()->withErrors(['error' => 'You cannot delete a role that is currently assigned to users.']);
        
        $role->delete();
        return back()->with('success', 'Custom role deleted.');
    }
}