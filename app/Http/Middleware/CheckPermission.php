<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();

        // Updated to super_admin
        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        if ($user && !$user->can_access($permission)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}