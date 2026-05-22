<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    // PASO 1
    // GUARDAR CORREO

    public function guardarCorreo(Request $request)
    {

        session([
            'correo_login' => $request->email
        ]);

        return redirect()->route('login.paso2');

    }


    // PASO 2
    // VALIDAR PASSWORD

    public function validarLogin(Request $request)
    {

        $correo = session('correo_login');

        $password = $request->password;

        // ADMIN

        if(
            $correo == 'admin@gmail.com'
            &&
            $password == '12345'
        ){

            return redirect()->route('admin_ini');

        }

        // USUARIO NORMAL

        return redirect()->route('inicio_principal');

    }

}