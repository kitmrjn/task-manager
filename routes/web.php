<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Go directly to the dashboard
Route::get('/', [TaskController::class, 'index']);