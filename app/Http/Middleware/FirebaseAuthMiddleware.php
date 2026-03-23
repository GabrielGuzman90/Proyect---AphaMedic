<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FirebaseAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('firebase_user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}