<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::check() && Auth::user()->hasAnyRole($roles)) {
            return $next($request);
        }

        return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
