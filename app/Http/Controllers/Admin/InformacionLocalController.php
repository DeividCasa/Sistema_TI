<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformacionLocal;
use Illuminate\Http\Request;

class InformacionLocalController extends Controller
{
    public function edit()
    {
        $info = InformacionLocal::actual();
        return view('Admin.informacion_local.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre_local'              => 'nullable|string|max:255',
            'descripcion'               => 'nullable|string',
            'direccion'                 => 'nullable|string|max:255',
            'horario'                   => 'nullable|string|max:255',
            'telefono'                  => 'nullable|string|max:50',
            'email_contacto'            => 'nullable|email|max:255',
            'imagen'                    => 'nullable|image|max:4096',
            'banner_titulo'             => 'nullable|string|max:255',
            'banner_subtitulo'          => 'nullable|string|max:255',
            'categoria_uniforme_imagen' => 'nullable|image|max:4096',
            'categoria_chompa_imagen'   => 'nullable|image|max:4096',
            'categoria_ropa_imagen'     => 'nullable|image|max:4096',
            'whatsapp_numero'           => 'nullable|string|max:20',
            'whatsapp_mensaje'          => 'nullable|string|max:255',
            'whatsapp_direccion'        => 'nullable|string|max:255',
            'whatsapp_horario'          => 'nullable|string|max:255',
            'visitanos_titulo'          => 'nullable|string|max:255',
            'visitanos_texto'           => 'nullable|string|max:500',
        ]);

        $info = InformacionLocal::actual();
        $info->fill($request->only([
            'nombre_local', 'descripcion', 'direccion', 'horario', 'telefono', 'email_contacto',
            'banner_titulo', 'banner_subtitulo',
            'whatsapp_numero', 'whatsapp_mensaje', 'whatsapp_direccion', 'whatsapp_horario',
            'visitanos_titulo', 'visitanos_texto',
        ]));

        if ($request->hasFile('imagen')) {
            $info->imagen_path = $request->file('imagen')->store('local', 'public');
        }

        foreach (['categoria_uniforme_imagen', 'categoria_chompa_imagen', 'categoria_ropa_imagen'] as $campo) {
            if ($request->hasFile($campo)) {
                $info->$campo = $request->file($campo)->store('local', 'public');
            }
        }

        $info->save();

        return back()->with('success', 'Información del local actualizada.');
    }
}
