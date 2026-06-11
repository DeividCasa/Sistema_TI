<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;
use App\Models\Administrador;

class LoginController extends Controller
{
    // ── Mostrar paso 1 (correo)
    public function showCorreo()
    {
        return view('login.login_correo');
    }

    // ── Validar correo
    public function verificarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'Ingresa un correo válido.',
        ]);

        // Buscar primero en administradores
        $admin = Administrador::where('email', $request->email)->first();
        if ($admin) {
            Session::put('login_email', $admin->email);
            Session::put('login_nombre', $admin->nombre);
            Session::put('login_rol', 'admin');
            return redirect()->route('login.paso2');
        }

        // Buscar en clientes
        $cliente = Cliente::where('email', $request->email)->first();
        if ($cliente) {
            Session::put('login_email', $cliente->email);
            Session::put('login_nombre', $cliente->nombre);
            Session::put('login_rol', 'cliente');
            return redirect()->route('login.paso2');
        }

        // No existe en ninguna tabla
        return back()->withErrors([
            'email' => 'No encontramos una cuenta con ese correo.'
        ])->withInput();
    }

    // ── Mostrar paso 2 (contraseña)
    public function showContrasena()
    {
        if (!Session::has('login_email')) {
            return redirect()->route('login.paso1');
        }

        return view('login.login_contra', [
            'email'  => Session::get('login_email'),
            'nombre' => Session::get('login_nombre'),
        ]);
    }

    public function verificarContrasena(Request $request)
{
    if (!Session::has('login_email')) {
        return redirect()->route('login.paso1');
    }

    $request->validate([
        'password' => 'required|min:6'
    ], [
        'password.required' => 'La contraseña es obligatoria.',
        'password.min'      => 'Mínimo 6 caracteres.',
    ]);

    $email = Session::get('login_email');
    $rol   = Session::get('login_rol');

    if ($rol === 'admin') {
        $usuario = Administrador::where('email', $email)->first();
    } else {
        $usuario = Cliente::where('email', $email)->first();
    }

    // Verificar contraseña
    if (!$usuario || !\Hash::check($request->password, $usuario->password)) {
        return back()->withErrors([
            'password' => 'Contraseña incorrecta.'
        ]);
    }

    // Guardar sesión
    Session::put('usuario_id',     $usuario->id);
    Session::put('usuario_nombre', $usuario->nombre);
    Session::put('usuario_rol',    $rol);
    Session::forget(['login_email', 'login_rol', 'login_nombre']);

    // Redirigir según rol
    if ($rol === 'admin') {
        return redirect()->route('admin.inicio');
    }

    return redirect()->route('cliente.inicio');
}
}