<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('usuario')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = DB::selectOne("
            SELECT
                U.ID_USUARIO,
                U.NOMBRE,
                U.CORREO,
                U.PASSWORD,
                U.ESTADO,
                R.NOMBRE_ROL
            FROM USUARIOS U
            INNER JOIN ROLES R ON U.ID_ROL = R.ID_ROL
            WHERE LOWER(U.CORREO) = LOWER(?)
            AND U.ESTADO = 'A'
        ", [$request->correo]);

        if (!$usuario) {
            return back()
                ->with('error', 'Correo o contraseña incorrectos.')
                ->withInput();
        }

$passwordGuardada = $usuario->password;

if (
    str_starts_with($passwordGuardada, '$2y$') ||
    str_starts_with($passwordGuardada, '$2a$') ||
    str_starts_with($passwordGuardada, '$2b$')
) {
    $passwordCorrecta = Hash::check($request->password, $passwordGuardada);
} else {
    $passwordCorrecta = $request->password === $passwordGuardada;
}

        if (!$passwordCorrecta) {
            return back()
                ->with('error', 'Correo o contraseña incorrectos.')
                ->withInput();
        }

        session([
            'usuario' => [
                'id_usuario' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'rol' => $usuario->nombre_rol
            ]
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('usuario');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}