<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateLastActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            DB::table('users')
                ->where('id', auth()->id())
                ->update(['last_active' => Carbon::now()]);
        }
        return $next($request);
    }
}