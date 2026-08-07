<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isAdmin()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'This action requires administrator privileges.'], 403);
            }

            return redirect()->route('login')->with('error', 'Please log in as an administrator.');
        }

        return $next($request);
    }
}
