<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
public function index()
    {
        if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
        }
         return view('settings');
    }
}