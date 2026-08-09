<?php

namespace App\Http\Middleware;

use App\Models\Administrador;
use App\Models\Cliente;
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

        // La sesión puede sobrevivir aunque el cliente/admin que representa
        // ya no exista en la base (cuenta borrada, base de datos reseteada
        // en desarrollo, etc.). Sin este chequeo, ese "usuario fantasma"
        // llega hasta un insert real (ej. confirmar un pedido) y truena con
        // un error de llave foránea en vez de simplemente pedirle que
        // vuelva a iniciar sesión.
        $modelo = session('usuario_rol') === 'admin' ? Administrador::class : Cliente::class;
        if (!$modelo::whereKey(session('usuario_id'))->exists()) {
            session()->forget(['usuario_id', 'usuario_nombre', 'usuario_rol']);
            return redirect()->route('login.paso1')
                             ->with('info', 'Tu sesión ya no es válida. Por favor, inicia sesión de nuevo.');
        }

        return $next($request);
    }
}