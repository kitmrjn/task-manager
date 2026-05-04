<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // RBAC Check: Must be at least a manager/team leader
        if (!$user->isAtLeastManager()) {
            abort(403, 'Unauthorized action.');
        }

        $query = TimeLog::with(['user', 'breaks'])->orderBy('log_date', 'desc');

        // RBAC Check: If Team Leader (not Admin), restrict to their team members
        if (!$user->isAtLeastAdmin()) {
            $teamMemberIds = User::where('team_leader_id', $user->id)->pluck('id');
            $query->whereIn('user_id', $teamMemberIds);
        }

        // Filters
        if ($request->has('date_from') && $request->date_from) {
            $query->where('log_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('log_date', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('team-logs.index', compact('logs'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAtLeastManager()) abort(403);

        $query = TimeLog::with(['user', 'breaks'])->orderBy('log_date', 'desc');

        if (!$user->isAtLeastAdmin()) {
            $teamMemberIds = User::where('team_leader_id', $user->id)->pluck('id');
            $query->whereIn('user_id', $teamMemberIds);
        }

        $logs = $query->get();

        $filename = "team_time_logs_" . now()->format('Y_m_d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Employee', 'Time In', 'Time Out', 'Break Time (mins)', 'Total Net Hours', 'Notes'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->log_date->format('Y-m-d'),
                    $log->user->name,
                    $log->time_in ? $log->time_in->format('h:i A') : '-',
                    $log->time_out ? $log->time_out->format('h:i A') : '-',
                    $log->total_break_minutes,
                    $log->total_hours,
                    $log->eod_notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}