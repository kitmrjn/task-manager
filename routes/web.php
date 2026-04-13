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
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tasks.index');

Route::middleware(['auth', 'verified'])->group(function () {

    // ── EOD Reports ────────────────────────────────────────────────────
    Route::get('/eod-reports', [App\Http\Controllers\EodReportController::class, 'index'])->name('eod.index');
    Route::get('/eod-reports/export', [App\Http\Controllers\EodReportController::class, 'export'])->name('eod.export');
    
    // ── Time Tracking (EOD) ────────────────────────────────────────────
    Route::post('/time-in', [App\Http\Controllers\TimeLogController::class, 'timeIn'])->name('time-logs.in');
    Route::post('/time-out', [App\Http\Controllers\TimeLogController::class, 'timeOut'])->name('time-logs.out');

    // ── Dashboard ──────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Email Integration ──────────────────────────────────────────────
    Route::get('/email', [App\Http\Controllers\EmailController::class, 'index'])->name('email.index');
    Route::post('/email/setup', [App\Http\Controllers\EmailController::class, 'storeSettings'])->name('email.setup.store');
    Route::get('/email/compose', [App\Http\Controllers\EmailController::class, 'compose'])->name('email.compose');
    Route::post('/email/send', [App\Http\Controllers\EmailController::class, 'send'])->name('email.send');
    Route::get('/email/unread', [App\Http\Controllers\EmailController::class, 'unreadCount'])->name('email.unread');
    
    Route::post('/email/{uid}/archive', [App\Http\Controllers\EmailController::class, 'archive'])->name('email.archive');
    Route::delete('/email/{uid}', [App\Http\Controllers\EmailController::class, 'destroy'])->name('email.destroy');
    Route::get('/email/attachment/{folder}/{uid}/{filename}', [App\Http\Controllers\EmailController::class, 'downloadAttachment'])->name('email.attachment.download');
    Route::get('/email/{uid}', [App\Http\Controllers\EmailController::class, 'show'])->name('email.show');

    // ── Task Actions ───────────────────────────────────────────────────
    Route::post('/tasks', [TaskController::class, 'store'])
        ->middleware('permission:can_create_tasks');
    Route::patch('/tasks/{task}/move', [TaskController::class, 'move']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->middleware('permission:can_delete_tasks');
    Route::get('/tasks/{task}/detail', [TaskController::class, 'detail'])->name('tasks.detail');

    Route::get('/dashboard/tasks', [App\Http\Controllers\DashboardController::class, 'getTasks']);
    
    // ── Profile ────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Memos ──────────────────────────────────────────────────────────
    Route::get('/memos', [App\Http\Controllers\MemoController::class, 'index'])->name('memos.index');
    Route::post('/memos', [App\Http\Controllers\MemoController::class, 'store'])->name('memos.store');
    Route::patch('/memos/{memo}/read', [App\Http\Controllers\MemoController::class, 'markRead'])->name('memos.read');
    Route::delete('/memos/{memo}', [App\Http\Controllers\MemoController::class, 'destroy'])->name('memos.destroy');
    Route::get('/memos/audience-options', [App\Http\Controllers\MemoController::class, 'audienceOptions'])->name('memos.audience');

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

    Route::delete('/admin/users/{user}/valid-ids', [UserController::class, 'deleteValidId'])
     ->name('admin.users.valid-ids.destroy');
    // ── Admin Only User Management ─────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');

        Route::get('/system-data', [App\Http\Controllers\Admin\SystemDataController::class, 'index'])->name('system-data.index');
        Route::post('/system-data/campaigns', [App\Http\Controllers\Admin\SystemDataController::class, 'storeCampaign'])->name('campaigns.store');
        Route::delete('/system-data/campaigns/{campaign}', [App\Http\Controllers\Admin\SystemDataController::class, 'destroyCampaign'])->name('campaigns.destroy');
        Route::post('/system-data/roles', [App\Http\Controllers\Admin\SystemDataController::class, 'storeRole'])->name('roles.store');
        Route::delete('/system-data/roles/{role}', [App\Http\Controllers\Admin\SystemDataController::class, 'destroyRole'])->name('roles.destroy');
        
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/resend-invite', [App\Http\Controllers\Admin\UserController::class, 'resendInvite'])->name('users.resend-invite');
    });


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
    Route::delete('/team/members/{user}', [TeamController::class, 'destroy'])->name('team.member.destroy');
    
    // ── Settings ───────────────────────────────────────────────────────
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');
    Route::post('/settings/branding', [App\Http\Controllers\SettingsController::class, 'updateBranding'])->name('settings.branding');
    Route::get('/settings/branding/clear/{key}', [SettingsController::class, 'clearBranding'])->name('settings.branding.clear');

    // ── Attachments ────────────────────────────────────────────────────
    Route::post('/tasks/{task}/attachments', [TaskAttachmentController::class, 'store']);
    Route::delete('/attachments/{attachment}', [TaskAttachmentController::class, 'destroy']);

    //── Verification Email ──────────────────────────────────────────────
    Route::get('verify-email', \App\Http\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');

    // ── Notifications ──────────────────────────────────────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
});

require __DIR__.'/auth.php';