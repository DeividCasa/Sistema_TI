<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Uniforme;

class UniformeClienteController extends Controller
{
    // ── DETALLE DE UN UNIFORME (elegir talla y cantidad)
    public function show($id)
    {
        $uniforme = Uniforme::with([
            'tallas' => function ($q) {
                $q->where('disponible', 1);
            }
        ])->where('activo', 1)->findOrFail($id);

        return view('cliente.show', compact('uniforme'));
    }
}