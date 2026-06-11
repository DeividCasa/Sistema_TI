<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;

class RegistroController extends Controller
{
    // ── MOSTRAR FORMULARIO
    public function show()
    {
        return view('login.registro');
    }

    // ── GUARDAR CLIENTE
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'email'             => 'required|email|unique:clientes,email',
            'telefono'          => 'nullable|string|max:20',
            'ciudad'            => 'nullable|string|max:100',
            'password'          => 'required|min:6|confirmed',
        ], [
            'nombre.required'            => 'El nombre es obligatorio.',
            'apellido.required'          => 'El apellido es obligatorio.',
            'email.required'             => 'El correo es obligatorio.',
            'email.unique'               => 'Ya existe una cuenta con ese correo.',
            'password.required'          => 'La contraseña es obligatoria.',
            'password.min'               => 'Mínimo 6 caracteres.',
            'password.confirmed'         => 'Las contraseñas no coinciden.',
        ]);

        Cliente::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'ciudad'   => $request->ciudad,
            'password' => Hash::make($request->password),
            'activo'   => 1,
        ]);

        return redirect()->route('login.paso1')
                         ->with('success', '¡Cuenta creada! Ya puedes iniciar sesión.');
    }
}