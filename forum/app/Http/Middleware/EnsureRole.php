<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Please log in to continue.');
        }

        $userRole = $user->role->value ?? $user->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
