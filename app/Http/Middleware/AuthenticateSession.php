<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthenticateSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('is_logged_in')) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect('/');
        }

        return $next($request);
    }
}
