<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Standard Dashboard
Route::get('/dashboard', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// NEW: Tasks page (using the same logic as dashboard for now)
Route::get('/tasks', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tasks.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // Task Actions
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}/move', [TaskController::class, 'move']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    
    // Profile Actions
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Dashboard
    Route::get('/dashboard', [BoardController::class, 'dashboard'])->name('dashboard');

    //Task
    Route::post('/columns', [BoardController::class, 'storeColumn'])->name('columns.store');
    Route::delete('/columns/{column}', [BoardController::class, 'destroyColumn'])->name('columns.destroy');
    
    //Column
    Route::patch('/columns/{column}/move', [BoardController::class, 'moveColumn'])->name('columns.move');
    Route::put('/columns/{column}', [BoardController::class, 'updateColumn'])->name('columns.update');
});

require __DIR__.'/auth.php';