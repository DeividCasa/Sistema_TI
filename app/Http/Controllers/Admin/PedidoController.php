<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // ── VER DETALLE DEL PEDIDO
    public function show($id)
    {
        $pedido = Pedido::with(['cliente', 'disenio', 'tallas', 'comprobantes', 'historial.administrador'])
                        ->findOrFail($id);
        return view('admin.pedidos.show', compact('pedido'));
    }

    // ── ACTUALIZAR ESTADO
    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:recibido,en_produccion,listo,enviado,entregado,cancelado'
        ]);

        // Guardar en historial
        \App\Models\HistorialEstado::create([
            'pedido_id'       => $pedido->id,
            'admin_id'        => session('usuario_id'),
            'estado_anterior' => $pedido->estado,
            'estado_nuevo'    => $request->estado,
            'nota'            => $request->nota,
        ]);

        $pedido->estado = $request->estado;
        $pedido->save();

        return redirect()->route('admin.pedidos.show', $pedido->id)
                         ->with('success', 'Estado actualizado correctamente.');
    }

    // ── MARCAR PAGO COMO COMPLETADO (override manual del admin)
    public function marcarPagoCompleto($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado_pago = 'pagado_completo';
        $pedido->save();

        return back()->with('success', 'Pago marcado como completado.');
    }
}