<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Models\Campaign;
use App\Models\User;
use App\Exports\TimeLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class EodReportController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildSecureQuery($request);
        
        $logs = $query->latest('log_date')->paginate(15)->withQueryString();
        
        // For Filter Dropdowns
        $campaigns = Campaign::orderBy('name')->get();
        
        // ONLY pull users into the dropdown who actually have team members assigned to them
        $teamLeaders = User::has('teamMembers')->orderBy('name')->get();

        return view('eod.index', compact('logs', 'campaigns', 'teamLeaders'));
    }

    public function export(Request $request)
    {
        $query = $this->buildSecureQuery($request);
        $filename = 'EOD_Report_' . now()->format('Ymd_Hi') . '.xlsx';
        
        return Excel::download(new TimeLogsExport($query), $filename);
    }

    /**
     * Centralized RBAC and Filtering Logic
     */
    private function buildSecureQuery(Request $request)
    {
        $user = auth()->user();
        $query = TimeLog::with(['user.campaign', 'user.teamLeader']);

        // 1. Enforce Role-Based Visibility
        if ($user->role === 'super_admin') {
            // View All - ONLY Super Admin has no restrictions
        } else {
            // Treat Managers and Regular Users the same: Check if they are a Team Leader
            $isLeader = User::where('team_leader_id', $user->id)->exists();
            
            if ($isLeader) {
                // They are a Team Leader: Can see themselves + ONLY their assigned subordinates
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('team_leader_id', $user->id)->orWhere('id', $user->id);
                });
            } else {
                // Regular member: Can only see their own logs
                $query->where('user_id', $user->id);
            }
        }

        // 2. Apply Search & Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('campaign')) {
            $campaignId = $request->campaign;
            $query->whereHas('user', function($q) use ($campaignId) {
                $q->where('campaign_id', $campaignId);
            });
        }
        if ($request->filled('leader')) {
            $leaderId = $request->leader;
            $query->whereHas('user', function($q) use ($leaderId) {
                $q->where('team_leader_id', $leaderId);
            });
        }
        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }

        return $query;
    }
}