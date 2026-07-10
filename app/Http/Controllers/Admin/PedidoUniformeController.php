<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PedidoUniforme;
use App\Models\ComprobanteUniforme;
use Illuminate\Http\Request;

class PedidoUniformeController extends Controller
{
    // ── LISTA DE PEDIDOS DE UNIFORMES
    public function index()
    {
        $pedidos = PedidoUniforme::with(['cliente', 'items.uniforme', 'comprobantes'])
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        return view('Admin.pedidos_uniformes.index', compact('pedidos'));
    }

    // ── DETALLE DEL PEDIDO (datos del cliente, items, comprobantes)
    public function show($id)
    {
        $pedido = PedidoUniforme::with(['cliente', 'items.uniforme', 'comprobantes'])
                                ->findOrFail($id);
        return view('Admin.pedidos_uniformes.show', compact('pedido'));
    }

    // ── CAMBIAR ESTADO DEL PEDIDO
    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:recibido,en_produccion,listo,enviado,entregado,cancelado',
        ]);

        $pedido = PedidoUniforme::findOrFail($id);
        $pedido->estado = $request->estado;
        if ($request->filled('observaciones')) {
            $pedido->observaciones = $request->observaciones;
        }
        $pedido->save();

        return back()->with('success', 'Estado del pedido actualizado.');
    }

    // ── VERIFICAR COMPROBANTE
    public function verificarComprobante($id)
    {
        $comprobante = ComprobanteUniforme::with('pedido')->findOrFail($id);
        $comprobante->estado = 'verificado';
        $comprobante->save();

        $pedido = $comprobante->pedido;

        if ($comprobante->tipo === 'adelanto') {
            $pedido->estado_pago = 'adelanto_verificado';
            if ($pedido->estado === 'recibido') {
                $pedido->estado = 'en_produccion';
            }
        } elseif ($comprobante->tipo === 'pago_completo') {
            $pedido->estado_pago = 'pagado_completo';
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
        $comprobante = ComprobanteUniforme::with('pedido')->findOrFail($id);
        $comprobante->estado = 'rechazado';
        $comprobante->nota_admin = $request->nota_admin ?? 'Comprobante no válido.';
        $comprobante->save();

        // Regresar el estado de pago para que el cliente pueda volver a subir
        $pedido = $comprobante->pedido;
        if ($comprobante->tipo === 'adelanto') {
            $pedido->estado_pago = 'pendiente';
        } elseif ($comprobante->tipo === 'pago_completo') {
            $pedido->estado_pago = 'pendiente';
        } elseif ($comprobante->tipo === 'saldo_final') {
            $pedido->estado_pago = 'adelanto_verificado';
        }
        $pedido->save();

        return back()->with('success', 'Comprobante rechazado.');
    }
}
