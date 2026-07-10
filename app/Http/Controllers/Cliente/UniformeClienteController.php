<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Uniforme;

class UniformeClienteController extends Controller
{
    // ── CATÁLOGO "UNIFORMES ESCOLARES"
    public function index()
    {
        $uniformes = Uniforme::with('tallas')
            ->where('activo', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.index', compact('uniformes'));
    }

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