<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskAttachmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tasks.index');

Route::middleware(['auth', 'verified'])->group(function () {


    // ── Dashboard ──────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Task Actions ───────────────────────────────────────────────────
    Route::post('/tasks', [TaskController::class, 'store'])
        ->middleware('permission:can_create_tasks');
    Route::patch('/tasks/{task}/move', [TaskController::class, 'move']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->middleware('permission:can_delete_tasks');
    Route::get('/tasks/{task}/detail', [TaskController::class, 'detail'])->name('tasks.detail');

    // ── Profile ────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Columns ────────────────────────────────────────────────────────
    Route::post('/columns', [BoardController::class, 'storeColumn'])
        ->middleware('permission:can_create_tasks')
        ->name('columns.store');
    Route::delete('/columns/{column}', [BoardController::class, 'destroyColumn'])
        ->middleware('permission:can_delete_tasks')
        ->name('columns.destroy');
    Route::patch('/columns/{column}/move', [BoardController::class, 'moveColumn'])->name('columns.move');
    Route::put('/columns/{column}', [BoardController::class, 'updateColumn'])->name('columns.update');

    // ── Checklist ──────────────────────────────────────────────────────
    Route::post('/tasks/{task}/checklist', [BoardController::class, 'storeChecklistItem'])->name('checklist.store');
    Route::patch('/checklist-items/{item}/toggle', [BoardController::class, 'toggleChecklistItem']);
    Route::delete('/checklist-items/{item}', [BoardController::class, 'destroyChecklistItem']);

    // ── Members ────────────────────────────────────────────────────────
    Route::post('/tasks/{task}/members/toggle', [BoardController::class, 'toggleMember']);

    // ── Mark as Complete ───────────────────────────────────────────────
    Route::patch('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete']);

    // ── Pages ──────────────────────────────────────────────────────────
    Route::get('/calendar', [CalendarController::class, 'index'])
        ->middleware('permission:can_view_calendar')
        ->name('calendar.index');
    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->middleware('permission:can_view_analytics')
        ->name('analytics.index');
    Route::get('/team', [TeamController::class, 'index'])
        ->middleware('permission:can_view_team')
        ->name('team.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');

// ── Calendar Events ────────────────────────────────────────────────
Route::post('/calendar/events', [CalendarController::class, 'storeEvent'])->name('calendar.events.store');
Route::delete('/calendar/events', [CalendarController::class, 'deleteEvent'])->name('calendar.events.delete');
Route::get('/holidays/{year}', [CalendarController::class, 'getHolidays'])->where('year', '[0-9]{4}');

Route::get('/holidays/us/{year}', [CalendarController::class, 'getUSHolidays']);
    
    // ── Comments ───────────────────────────────────────────────────────
    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comment');

    // ── Team ───────────────────────────────────────────────────────────
    Route::get('/team/{user}/tasks', [TeamController::class, 'memberTasks'])->name('team.tasks');
    Route::patch('/team/{user}/role', [TeamController::class, 'updateRole'])->name('team.role');
    Route::put('/team/members/{user}', [TeamController::class, 'update'])->name('team.member.update');
    Route::patch('/team/members/{user}/permissions', [TeamController::class, 'updatePermissions'])->name('team.permissions');

    // ── Settings ───────────────────────────────────────────────────────
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');

    // ── Attachments ────────────────────────────────────────────────────
    Route::post('/tasks/{task}/attachments', [TaskAttachmentController::class, 'store']);
    Route::delete('/attachments/{attachment}', [TaskAttachmentController::class, 'destroy']);

    Route::post('/settings/branding', [App\Http\Controllers\SettingsController::class, 'updateBranding'])->name('settings.branding');
    Route::get('/settings/branding/clear/{key}', [SettingsController::class, 'clearBranding'])->name('settings.branding.clear');

    // ── Notifications ──────────────────────────────────────────────────
    Route::get('/notifications', function () {
        $notifications = \App\Models\TaskActivity::with(['user', 'task'])
            ->whereHas('task', function ($q) {
                $q->where('assigned_to', auth()->id())
                ->orWhereHas('members', fn ($q) => $q->where('users.id', auth()->id()));
            })
            ->where('user_id', '!=', auth()->id()) // ← don't notify yourself
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications->map(fn ($a) => [
            'description' => $a->description,
            'user'        => $a->user?->name,
            'task'        => $a->task?->title,
            'time'        => \Carbon\Carbon::parse($a->created_at)->diffForHumans(),
            'action'      => $a->action,
        ])->values());
    })->name('notifications');
});

require __DIR__.'/auth.php';