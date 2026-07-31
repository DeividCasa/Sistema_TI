<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\Cliente;
use App\Support\CedulaEcuador;

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
            'nombre'   => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'cedula'   => [
                'required', 'digits:10', 'unique:clientes,cedula',
                function ($attribute, $value, $fail) {
                    if (!CedulaEcuador::esValida($value)) {
                        $fail('La cédula ingresada no es válida.');
                    }
                },
            ],
            'email'    => 'required|email:rfc,filter|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
            'ciudad'   => 'nullable|string|max:100',
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/'],
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'nombre.regex'      => 'El nombre solo puede contener letras.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex'    => 'El apellido solo puede contener letras.',
            'cedula.required'   => 'La cédula es obligatoria.',
            'cedula.digits'     => 'La cédula debe tener 10 dígitos.',
            'cedula.unique'     => 'Ya existe una cuenta con esa cédula.',
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'email.unique'      => 'Ya existe una cuenta con ese correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.regex'    => 'Mínimo 8 caracteres, con mayúscula, minúscula, número y símbolo (ej: Deivid21$).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        Cliente::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'cedula'   => $request->cedula,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'ciudad'   => $request->ciudad,
            'password' => Hash::make($request->password),
            'activo'   => 1,
        ]);

        return redirect()->route('login.paso1')
                         ->with('success', '¡Cuenta creada! Ya puedes iniciar sesión.');
    }

    // ── CONSULTAR NOMBRES/APELLIDOS A PARTIR DE LA CÉDULA (autocompletado, "mejor esfuerzo")
    // Usa un endpoint interno de SECAP no documentado oficialmente como API pública:
    // si falla, cambia de formato o deja de responder, el registro sigue funcionando
    // normal, el cliente solo tiene que escribir su nombre a mano.
    public function consultarCedula(Request $request)
    {
        $request->validate(['cedula' => 'required|digits:10']);

        if (!CedulaEcuador::esValida($request->cedula)) {
            return response()->json(['encontrado' => false]);
        }

        try {
            $respuesta = Http::asForm()->timeout(5)->post(
                'https://si.secap.gob.ec/sisecap/logeo_web/json/busca_persona_registro_civil.php',
                ['documento' => $request->cedula, 'tipo' => '1']
            );

            $datos = $respuesta->json();

            if (!empty($datos['nombres']) && !empty($datos['apellidos'])) {
                return response()->json([
                    'encontrado' => true,
                    'nombres'    => $datos['nombres'],
                    'apellidos'  => $datos['apellidos'],
                ]);
            }
        } catch (\Throwable $e) {
            // Servicio externo no disponible: no es un error del usuario, solo no autocompletamos.
        }

        return response()->json(['encontrado' => false]);
    }
}
