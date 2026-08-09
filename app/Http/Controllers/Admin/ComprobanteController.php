<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComprobantePago;
use App\Models\Pedido;
use Illuminate\Http\Request;

class ComprobanteController extends Controller
{
    public function verificar($id)
    {
        $comprobante = ComprobantePago::findOrFail($id);
        $comprobante->estado = 'verificado';
        $comprobante->save();

        $pedido = Pedido::findOrFail($comprobante->pedido_id);

        $pedido->estado_pago = $comprobante->tipo === 'adelanto'
            ? 'adelanto_verificado'
            : 'pagado_completo';

        $estadoAnterior = $pedido->estado;
        if ($pedido->estado === 'recibido') {
            $pedido->estado = 'en_produccion';
        }
        $pedido->save();

        if ($estadoAnterior !== $pedido->estado) {
            \App\Models\HistorialEstado::create([
                'pedido_id'       => $pedido->id,
                'admin_id'        => session('usuario_id'),
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $pedido->estado,
                'nota'            => 'Comprobante de '.str_replace('_', ' ', $comprobante->tipo).' verificado.',
            ]);
        }

        return back()->with('success', 'Comprobante verificado correctamente.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'nota' => 'nullable|string|max:1000',
        ]);

        $comprobante = ComprobantePago::findOrFail($id);
        $comprobante->estado     = 'rechazado';
        $comprobante->nota_admin = $request->nota ?? 'Comprobante rechazado.';
        $comprobante->save();

        $pedido = Pedido::findOrFail($comprobante->pedido_id);
        // Si rechazan el comprobante del saldo final, el adelanto ya verificado
        // se mantiene — solo se regresa al estado anterior a ESE pago.
        $pedido->estado_pago = $comprobante->tipo === 'saldo_final'
            ? 'adelanto_verificado'
            : 'pendiente';
        $pedido->save();

        return back()->with('success', 'Comprobante rechazado.');
    }
}