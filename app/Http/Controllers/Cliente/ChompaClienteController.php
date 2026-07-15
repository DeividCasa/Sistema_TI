<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Chompa;

class ChompaClienteController extends Controller
{
    // ── DETALLE DE UNA CHOMPA (elegir talla y cantidad)
    public function show($id)
    {
        $chompa = Chompa::with([
            'tallas' => function ($q) {
                $q->where('disponible', 1);
            }
        ])->where('activo', 1)->findOrFail($id);

        return view('cliente.chompas.show', compact('chompa'));
    }
}
