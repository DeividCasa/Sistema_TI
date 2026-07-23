<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Support\Notificaciones;

class ClienteController extends Controller
{
    // ── LISTA DE CLIENTES
    public function index()
    {
        Notificaciones::marcarVisto('clientes');

        $clientes = Cliente::withCount('pedidos')
                           ->orderBy('created_at', 'desc')
                           ->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    // ── VER DETALLE
    public function show($id)
    {
        $cliente = Cliente::with(['pedidos.disenio'])
                          ->findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }
}