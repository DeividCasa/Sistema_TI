<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Chompa;

class ChompaClienteController extends Controller
{
    // ── CATÁLOGO DE CHOMPAS
    public function index()
    {
        $chompas = Chompa::with('tallas')
            ->where('activo', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.chompas.index', compact('chompas'));
    }

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
