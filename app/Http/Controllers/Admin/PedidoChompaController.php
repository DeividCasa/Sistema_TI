<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PedidoChompa;
use App\Models\ComprobanteChompa;
use Illuminate\Http\Request;

class PedidoChompaController extends Controller
{
    // ── LISTA DE PEDIDOS DE CHOMPAS
    public function index()
    {
        $pedidos = PedidoChompa::with(['cliente', 'items.chompa', 'comprobantes'])
                               ->orderBy('created_at', 'desc')
                               ->get();
        return view('Admin.pedidos_chompas.index', compact('pedidos'));
    }

    // ── DETALLE DEL PEDIDO
    public function show($id)
    {
        $pedido = PedidoChompa::with(['cliente', 'items.chompa', 'comprobantes'])
                              ->findOrFail($id);
        return view('Admin.pedidos_chompas.show', compact('pedido'));
    }

    // ── CAMBIAR ESTADO DEL PEDIDO
    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:recibido,en_produccion,listo,enviado,entregado,cancelado',
        ]);

        $pedido = PedidoChompa::findOrFail($id);
        $pedido->estado = $request->estado;
        if ($request->filled('observaciones')) {
            $pedido->observaciones = $request->observaciones;
        }
        $pedido->save();

        return back()->with('success', 'Estado del pedido actualizado.');
    }

    // ── MARCAR PAGO COMO COMPLETADO (override manual del admin)
    public function marcarPagoCompleto($id)
    {
        $pedido = PedidoChompa::findOrFail($id);
        $pedido->estado_pago = 'pagado_completo';
        $pedido->save();

        return back()->with('success', 'Pago marcado como completado.');
    }

    // ── VERIFICAR COMPROBANTE
    public function verificarComprobante($id)
    {
        $comprobante = ComprobanteChompa::with('pedido')->findOrFail($id);
        $comprobante->estado = 'verificado';
        $comprobante->save();

        $pedido = $comprobante->pedido;

        if (in_array($comprobante->tipo, ['adelanto', 'pago_completo'])) {
            $pedido->estado_pago = $comprobante->tipo === 'pago_completo'
                ? 'pagado_completo'
                : 'adelanto_verificado';
            if ($pedido->estado === 'recibido') {
                $pedido->estado = 'en_produccion';
            }
        } elseif ($comprobante->tipo === 'saldo_final') {
            $pedido->estado_pago = 'pagado_completo';
        }
        $pedido->save();

        return back()->with('success', 'Comprobante verificado correctamente.');
    }

    // ── RECHAZAR COMPROBANTE
    public function rechazarComprobante(Request $request, $id)
    {
        $comprobante = ComprobanteChompa::with('pedido')->findOrFail($id);
        $comprobante->estado    = 'rechazado';
        $comprobante->nota_admin = $request->nota_admin ?? 'Comprobante no válido.';
        $comprobante->save();

        $pedido = $comprobante->pedido;
        if ($comprobante->tipo === 'saldo_final') {
            $pedido->estado_pago = 'adelanto_verificado';
        } else {
            $pedido->estado_pago = 'pendiente';
        }
        $pedido->save();

        return back()->with('success', 'Comprobante rechazado.');
    }
}
