<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!session()->has('usuario')) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para ingresar al sistema.');
        }

        $rolUsuario = session('usuario.rol');

        if (!in_array($rolUsuario, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
