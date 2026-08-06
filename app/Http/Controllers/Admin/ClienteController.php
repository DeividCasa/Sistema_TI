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

        $clientes = Cliente::withCount([
                                'pedidos',
                                'pedidosMaestro',
                                'pedidosUniforme' => fn ($q) => $q->whereNull('pedido_maestro_id'),
                                'pedidosChompa' => fn ($q) => $q->whereNull('pedido_maestro_id'),
                                'pedidosPlantilla' => fn ($q) => $q->whereNull('pedido_maestro_id'),
                            ])
                           ->orderBy('created_at', 'desc')
                           ->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    // ── VER DETALLE
    public function show($id)
    {
        $cliente = Cliente::with(['pedidos.disenio'])
                          ->findOrFail($id);
        // Unificado: cotizaciones (camisetas personalizadas) + pedidos de tienda
        // (uniforme/chompa/ropa/combinado), todo bajo el mismo "Pedidos realizados".
        $pedidos = PedidoTiendaController::pedidosUnificados($cliente->id);
        return view('admin.clientes.show', compact('cliente', 'pedidos'));
    }
}