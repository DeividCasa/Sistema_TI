<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Testimonio;
use Illuminate\Http\Request;

class TestimonioController extends Controller
{
    // ── FORMULARIO PARA DEJAR UNA OPINIÓN
    public function create()
    {
        $yaOpino = Testimonio::where('cliente_id', session('usuario_id'))->exists();
        return view('cliente.testimonios.create', compact('yaOpino'));
    }

    // ── GUARDAR OPINIÓN DEL CLIENTE
    public function store(Request $request)
    {
        if (Testimonio::where('cliente_id', session('usuario_id'))->exists()) {
            return redirect()->route('cliente.testimonios.create');
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'texto'        => 'required|string|max:600',
        ], [
            'calificacion.required' => 'Selecciona una calificación.',
            'texto.required'        => 'Cuéntanos tu experiencia.',
        ]);

        Testimonio::create([
            'cliente_id'     => session('usuario_id'),
            'nombre_cliente' => session('usuario_nombre'),
            'texto'          => $request->texto,
            'calificacion'   => $request->calificacion,
            'estado'         => 'pendiente',
            'activo'         => 0,
        ]);

        return redirect()->route('cliente.testimonios.create')
                         ->with('success', '¡Gracias por tu opinión! La revisaremos antes de publicarla.');
    }
}
