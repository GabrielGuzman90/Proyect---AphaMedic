<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('firebase_user');

        // Si no hay sesión → solo bloquear, NO redirigir
        if (!$user) {
            abort(403, 'Debes iniciar sesión.');
        }

        // Si no es admin → bloquear
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            abort(403, 'No tienes permisos para acceder aquí.');
        }

        return $next($request);
    }
}