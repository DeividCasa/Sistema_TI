<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;
use App\Models\Administrador;
use App\Models\PasswordResetCode;
use App\Mail\CodigoRecuperacionMail;

class PasswordResetController extends Controller
{
    // ── Paso 1: pedir el correo
    public function showSolicitar()
    {
        return view('login.password_olvide');
    }

    public function enviarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'Ingresa un correo válido.',
        ]);

        [$usuario, $rol, $nombre] = $this->buscarUsuario($request->email);

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'No encontramos una cuenta con ese correo.'
            ])->withInput();
        }

        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::where('email', $request->email)->delete();

        PasswordResetCode::create([
            'email'      => $request->email,
            'rol'        => $rol,
            'code'       => $codigo,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new CodigoRecuperacionMail($nombre, $codigo));

        Session::put('reset_email', $request->email);

        return redirect()->route('password.codigo');
    }

    // ── Paso 2: código + nueva contraseña
    public function showCodigo()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.solicitar');
        }

        return view('login.password_codigo', [
            'email' => Session::get('reset_email'),
        ]);
    }

    public function restablecer(Request $request)
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.solicitar');
        }

        $request->validate([
            'codigo'   => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ], [
            'codigo.required'     => 'El código es obligatorio.',
            'codigo.digits'       => 'El código debe tener 6 dígitos.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'Mínimo 6 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
        ]);

        $email = Session::get('reset_email');

        $registro = PasswordResetCode::where('email', $email)
            ->where('code', $request->codigo)
            ->first();

        if (!$registro) {
            return back()->withErrors([
                'codigo' => 'El código ingresado es incorrecto.'
            ]);
        }

        if (now()->greaterThan($registro->expires_at)) {
            return back()->withErrors([
                'codigo' => 'El código ha expirado. Solicita uno nuevo.'
            ]);
        }

        if ($registro->rol === 'admin') {
            Administrador::where('email', $email)->update([
                'password' => Hash::make($request->password),
            ]);
        } else {
            Cliente::where('email', $email)->update([
                'password' => Hash::make($request->password),
            ]);
        }

        PasswordResetCode::where('email', $email)->delete();
        Session::forget('reset_email');

        return redirect()->route('login.paso1')
                         ->with('success', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');
    }

    // ── Reenviar código
    public function reenviarCodigo()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.solicitar');
        }

        $email = Session::get('reset_email');
        [$usuario, $rol, $nombre] = $this->buscarUsuario($email);

        if (!$usuario) {
            return redirect()->route('password.solicitar');
        }

        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::where('email', $email)->delete();

        PasswordResetCode::create([
            'email'      => $email,
            'rol'        => $rol,
            'code'       => $codigo,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new CodigoRecuperacionMail($nombre, $codigo));

        return back()->with('info', 'Te enviamos un nuevo código a tu correo.');
    }

    private function buscarUsuario(string $email): array
    {
        $admin = Administrador::where('email', $email)->first();
        if ($admin) {
            return [$admin, 'admin', $admin->nombre];
        }

        $cliente = Cliente::where('email', $email)->first();
        if ($cliente) {
            return [$cliente, 'cliente', $cliente->nombre];
        }

        return [null, null, null];
    }
}
