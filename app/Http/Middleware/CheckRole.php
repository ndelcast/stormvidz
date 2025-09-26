<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $panel): Response
    {
        // Skip role check for login routes
        if (str_contains($request->path(), 'login')) {
            return $next($request);
        }

        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($panel === 'admin' && $user->role !== 'admin') {
            abort(403, 'Access denied.');
        }

        if ($panel === 'app' && !in_array($user->role, ['user', 'admin'])) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
