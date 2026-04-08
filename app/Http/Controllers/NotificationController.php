<?php

namespace App\Http\Controllers;

use App\Models\TaskActivity;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Fetch the latest task activity notifications for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $notifications = TaskActivity::with(['user', 'task'])
            ->whereHas('task', function ($q) {
                $q->where('assigned_to', auth()->id())
                  ->orWhereHas('members', fn ($q) => $q->where('users.id', auth()->id()));
            })
            ->where('user_id', '!=', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $formatted = $notifications->map(fn ($a) => [
            'description' => $a->description,
            'user'        => $a->user?->name,
            'task'        => $a->task?->title,
            'time'        => Carbon::parse($a->created_at)->diffForHumans(),
            'action'      => $a->action,
        ])->values();

        return response()->json($formatted);
    }
}