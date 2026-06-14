<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSesion
{
    public function handle(Request $request, Closure $next, string $rol = null)
    {
        if (!session('usuario_id')) {
            return redirect()->route('login.paso1')
                             ->with('info', 'Debes iniciar sesión para continuar.');
        }

        if ($rol && session('usuario_rol') !== $rol) {
            if (session('usuario_rol') === 'admin') {
                return redirect()->route('admin.inicio');
            }
            return redirect()->route('cliente.inicio');
        }

        return $next($request);
    }
}