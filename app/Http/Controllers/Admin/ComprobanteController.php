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
        $pedido->estado_pago = 'adelanto_verificado';
        $pedido->estado      = 'en_produccion';
        $pedido->save();

        \App\Models\HistorialEstado::create([
            'pedido_id'       => $pedido->id,
            'admin_id'        => session('usuario_id'),
            'estado_anterior' => 'recibido',
            'estado_nuevo'    => 'en_produccion',
            'nota'            => 'Comprobante de adelanto verificado.',
        ]);

        return back()->with('success', 'Comprobante verificado. Pedido en producción.');
    }

    public function rechazar(Request $request, $id)
    {
        $comprobante = ComprobantePago::findOrFail($id);
        $comprobante->estado     = 'rechazado';
        $comprobante->nota_admin = $request->nota ?? 'Comprobante rechazado.';
        $comprobante->save();

        $pedido = Pedido::findOrFail($comprobante->pedido_id);
        $pedido->estado_pago = 'pendiente';
        $pedido->save();

        return back()->with('error', 'Comprobante rechazado.');
    }
}