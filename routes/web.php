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

Route::get('/', function () {
    return view('welcome');
});

// Standard Dashboard
Route::get('/dashboard', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Tasks page
Route::get('/tasks', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tasks.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // Task Actions
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}/move', [TaskController::class, 'move']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/tasks/{task}/detail', [TaskController::class, 'detail'])->name('tasks.detail'); // ← NEW

    // Profile Actions
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', [BoardController::class, 'dashboard'])->name('dashboard');

    // Column
    Route::post('/columns', [BoardController::class, 'storeColumn'])->name('columns.store');
    Route::delete('/columns/{column}', [BoardController::class, 'destroyColumn'])->name('columns.destroy');
    Route::patch('/columns/{column}/move', [BoardController::class, 'moveColumn'])->name('columns.move');
    Route::put('/columns/{column}', [BoardController::class, 'updateColumn'])->name('columns.update');

    // Checklist
    Route::post('/tasks/{task}/checklist', [BoardController::class, 'storeChecklistItem'])->name('checklist.store');
    Route::patch('/checklist-items/{item}/toggle', [BoardController::class, 'toggleChecklistItem']);
    Route::delete('/checklist-items/{item}', [BoardController::class, 'destroyChecklistItem']);

    // Members
    Route::post('/tasks/{task}/members/toggle', [BoardController::class, 'toggleMember']);

    // Mark as Complete
    Route::patch('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete']);

    // ── New Pages ──────────────────────────────────────
    Route::get('/calendar',  [CalendarController::class,  'index'])->name('calendar.index');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/team',      [TeamController::class,      'index'])->name('team.index');
    Route::get('/settings',  [SettingsController::class,  'index'])->name('settings.index');
    Route::get('/help',      [HelpController::class,      'index'])->name('help.index');

});

require __DIR__.'/auth.php';