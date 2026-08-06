<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;

class PlantillaClienteController extends Controller
{
    // ── DETALLE DE UNA PRENDA (elegir talla y cantidad)
    public function show($id)
    {
        $plantilla = Plantilla::with([
            'tallas' => function ($q) {
                $q->where('disponible', 1);
            }
        ])->where('activa', 1)->findOrFail($id);

        return view('cliente.producto', compact('plantilla'));
    }
}
