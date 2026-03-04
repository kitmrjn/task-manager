<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BoardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route for creating a new task
Route::post('/tasks', [TaskController::class, 'store'])->middleware(['auth', 'verified']);
// Route for updating a task's column via drag-and-drop
Route::patch('/tasks/{task}/move', [TaskController::class, 'move'])->middleware(['auth', 'verified']);
// Update an existing task
Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware(['auth', 'verified']);
// Delete a task
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
