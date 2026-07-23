<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;
use App\Models\Administrador;
use App\Mail\CodigoAccesoMail;

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
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'password' => 'Contraseña incorrecta.'
            ]);
        }

        // ── Los administradores requieren un código de acceso enviado a su correo
        if ($rol === 'admin') {
            $this->enviarCodigoAcceso($usuario);
            return redirect()->route('login.paso3');
        }

        // Guardar sesión (clientes entran directo)
        Session::put('usuario_id',     $usuario->id);
        Session::put('usuario_nombre', $usuario->nombre);
        Session::put('usuario_rol',    $rol);
        Session::forget(['login_email', 'login_rol', 'login_nombre']);

        return redirect()->route('cliente.inicio');
    }

    // ── Mostrar paso 3 (código de acceso, solo admin)
    public function showCodigo()
    {
        if (!Session::has('login_email') || Session::get('login_rol') !== 'admin' || !Session::has('login_codigo_expira')) {
            return redirect()->route('login.paso1');
        }

        return view('login.login_codigo', [
            'email'  => Session::get('login_email'),
            'nombre' => Session::get('login_nombre'),
        ]);
    }

    // ── Validar código de acceso
    public function verificarCodigo(Request $request)
    {
        if (!Session::has('login_email') || Session::get('login_rol') !== 'admin') {
            return redirect()->route('login.paso1');
        }

        $request->validate([
            'codigo' => 'required|digits:6',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.digits'   => 'El código debe tener 6 dígitos.',
        ]);

        $expira = Session::get('login_codigo_expira');

        if (!$expira || now()->greaterThan($expira)) {
            return back()->withErrors([
                'codigo' => 'El código ha expirado. Solicita uno nuevo.'
            ]);
        }

        if ($request->codigo !== Session::get('login_codigo')) {
            return back()->withErrors([
                'codigo' => 'El código ingresado es incorrecto.'
            ]);
        }

        $email  = Session::get('login_email');
        $nombre = Session::get('login_nombre');
        $usuario = Administrador::where('email', $email)->first();

        if (!$usuario) {
            return redirect()->route('login.paso1');
        }

        Session::put('usuario_id',     $usuario->id);
        Session::put('usuario_nombre', $usuario->nombre);
        Session::put('usuario_rol',    'admin');
        Session::forget(['login_email', 'login_rol', 'login_nombre', 'login_codigo', 'login_codigo_expira']);

        return redirect()->route('admin.inicio');
    }

    // ── Reenviar código de acceso
    public function reenviarCodigo()
    {
        if (!Session::has('login_email') || Session::get('login_rol') !== 'admin') {
            return redirect()->route('login.paso1');
        }

        $usuario = Administrador::where('email', Session::get('login_email'))->first();

        if (!$usuario) {
            return redirect()->route('login.paso1');
        }

        $this->enviarCodigoAcceso($usuario);

        return back()->with('info', 'Te enviamos un nuevo código a tu correo.');
    }

    private function enviarCodigoAcceso(Administrador $admin): void
    {
        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Session::put('login_codigo', $codigo);
        Session::put('login_codigo_expira', now()->addMinutes(10));

        Mail::to($admin->email)->send(new CodigoAccesoMail($admin->nombre, $codigo));
    }
}
