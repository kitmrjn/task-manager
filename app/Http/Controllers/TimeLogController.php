<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{
    /**
     * Handle the Time In action.
     */
    public function timeIn(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Check if the user already timed in today
        $existingLog = TimeLog::where('user_id', $user->id)
                              ->where('log_date', $today)
                              ->first();

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

    /**
     * Handle the Time Out action.
     */
    public function timeOut(Request $request)
    {
        $request->validate([
            'eod_notes' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();

        $log = TimeLog::where('user_id', $user->id)
                      ->where('log_date', $today)
                      ->first();

        if (!$log) {
            return back()->withErrors(['error' => 'You must time in before you can time out.']);
        }

        if ($log->time_out) {
            return back()->withErrors(['error' => 'You have already timed out for today.']);
        }

        $log->update([
            'time_out'  => now(),
            'eod_notes' => $request->eod_notes,
        ]);

        return back()->with('success', 'You have successfully timed out. Great work today!');
    }
}