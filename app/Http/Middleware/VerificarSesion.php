<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario')) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para ingresar al sistema.');
        }

        return $next($request);
    }
}