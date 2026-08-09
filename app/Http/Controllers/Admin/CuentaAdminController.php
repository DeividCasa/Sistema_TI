<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CuentaAdminController extends Controller
{
    public function actualizarPassword(Request $request)
    {
        $admin = Administrador::findOrFail(session('usuario_id'));

        $request->validate([
            'password_actual' => 'required',
            'password_nuevo'  => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/'],
        ], [
            'password_actual.required' => 'Ingresa tu contraseña actual.',
            'password_nuevo.required'  => 'Ingresa la nueva contraseña.',
            'password_nuevo.regex'     => 'Mínimo 8 caracteres, con mayúscula, minúscula, número y símbolo (ej: Ejemplo123$).',
            'password_nuevo.confirmed' => 'La confirmación no coincide con la nueva contraseña.',
        ]);

        if (!Hash::check($request->password_actual, $admin->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.'])->withInput();
        }

        $admin->password = Hash::make($request->password_nuevo);
        $admin->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
