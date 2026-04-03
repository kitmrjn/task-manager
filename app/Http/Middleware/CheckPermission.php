<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();

        // Bypass all permission checks for Super Admin inherently
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user && !$user->can_access($permission)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}