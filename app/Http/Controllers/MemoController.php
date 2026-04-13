<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\MemoRead;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    /**
     * Return memos visible to the authenticated user.
     */
    public function index()
    {
        $user  = auth()->user();
        $memos = Memo::with(['creator', 'reads'])
            ->where(function ($q) use ($user) {
                $q->where('target_type', 'all')
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('target_type', 'campaign')
                         ->where('target_id', $user->campaign_id);
                  })
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('target_type', 'user')
                         ->where('target_id', $user->id);
                  });
            })
            ->latest()
            ->get()
            ->map(function ($memo) use ($user) {
                $isRead = $memo->reads->contains('user_id', $user->id);

                $audience = match($memo->target_type) {
                    'all'      => 'Everyone',
                    'campaign' => 'Campaign: ' . (Campaign::find($memo->target_id)?->name ?? 'Unknown'),
                    'user'     => 'Personal: ' . (User::find($memo->target_id)?->name ?? 'Unknown'),
                    default    => 'Everyone',
                };

                return [
                    'id'         => $memo->id,
                    'title'      => $memo->title,
                    'content'    => $memo->content,
                    'creator'    => $memo->creator?->name ?? 'Unknown',
                    'audience'   => $audience,
                    'is_read'    => $isRead,
                    'created_at' => $memo->created_at->diffForHumans(),
                    'can_delete' => auth()->user()->isAtLeastManager(),
                ];
            });

        return response()->json($memos);
    }

    /**
     * Store a new memo. Manager and Super Admin only.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAtLeastManager()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|max:2000',
            'target_type' => 'required|in:all,campaign,user',
            'target_id'   => 'nullable|integer',
        ]);

        Memo::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'created_by'  => auth()->id(),
            'target_type' => $request->target_type,
            'target_id'   => $request->target_type === 'all' ? null : $request->target_id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark a memo as read for the current user.
     */
    public function markRead(Memo $memo)
    {
        MemoRead::firstOrCreate([
            'memo_id' => $memo->id,
            'user_id' => auth()->id(),
        ], [
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a memo. Manager and Super Admin only.
     */
    public function destroy(Memo $memo)
    {
        if (!auth()->user()->isAtLeastManager()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $memo->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Return campaigns and users for the audience picker.
     */
    public function audienceOptions()
    {
        if (!auth()->user()->isAtLeastManager()) {
            return response()->json(['success' => false], 403);
        }

        $campaigns = Campaign::select('id', 'name')->orderBy('name')->get();
        $users     = User::select('id', 'name', 'campaign_id')
            ->orderBy('name')
            ->get()
            ->map(fn($u) => [
                'id'          => $u->id,
                'name'        => $u->name,
                'campaign_id' => $u->campaign_id,
            ]);

        return response()->json([
            'campaigns' => $campaigns,
            'users'     => $users,
        ]);
    }
}