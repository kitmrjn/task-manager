<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Models\BreakLog;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    public function timeIn(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $existingLog = TimeLog::where('user_id', $user->id)->where('log_date', $today)->first();

        if ($existingLog) {
            return back()->withErrors(['error' => 'You have already timed in today.']);
        }

        TimeLog::create([
            'user_id'  => $user->id,
            'log_date' => $today,
            'time_in'  => now(),
        ]);

        return back()->with('success', 'You have successfully timed in! Have a great shift.');
    }

    public function startBreak(Request $request)
    {
        $request->validate(['break_type' => 'required|in:first,lunch,last']);
        $user = Auth::user();
        $today = now()->toDateString();

        $log = TimeLog::where('user_id', $user->id)->where('log_date', $today)->first();

        if (!$log || $log->time_out) {
            return back()->withErrors(['error' => 'Invalid time log state.']);
        }

        // Check if already on break
        if ($log->breaks()->whereNull('end_time')->exists()) {
            return back()->withErrors(['error' => 'You are already on a break.']);
        }

        $log->breaks()->create([
            'break_type' => $request->break_type,
            'start_time' => now()
        ]);

        return back()->with('success', 'Break started.');
    }

    public function endBreak(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $log = TimeLog::where('user_id', $user->id)->where('log_date', $today)->first();
        if (!$log) return back()->withErrors(['error' => 'No active time log found.']);

        $activeBreak = $log->breaks()->whereNull('end_time')->first();
        
        if (!$activeBreak) {
            return back()->withErrors(['error' => 'You are not currently on a break.']);
        }

        $endTime = now();
        $duration = $activeBreak->start_time->diffInMinutes($endTime);

        $activeBreak->update([
            'end_time' => $endTime,
            'duration_minutes' => $duration
        ]);

        return back()->with('success', 'Welcome back! Break ended.');
    }

    public function timeOut(Request $request)
    {
        $request->validate(['eod_notes' => 'required|string|max:1000']);
        $user = Auth::user();
        $today = now()->toDateString();

        $log = TimeLog::where('user_id', $user->id)->where('log_date', $today)->first();

        if (!$log || $log->time_out) {
            return back()->withErrors(['error' => 'Invalid state to time out.']);
        }

        // Auto-end any active break
        $activeBreak = $log->breaks()->whereNull('end_time')->first();
        if ($activeBreak) {
            $endTime = now();
            $activeBreak->update([
                'end_time' => $endTime,
                'duration_minutes' => $activeBreak->start_time->diffInMinutes($endTime)
            ]);
        }

        $log->update([
            'time_out'  => now(),
            'eod_notes' => $request->eod_notes,
        ]);

        return back()->with('success', 'You have successfully timed out. Great work today!');
    }
}