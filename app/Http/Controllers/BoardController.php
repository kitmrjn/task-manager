<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $board = Board::with(['columns.tasks.assignee'])->first();
        $users = User::all(); // Fetch all team members

        // Pass both the board and users to the view
        return view('dashboard', compact('board', 'users'));
    }
}