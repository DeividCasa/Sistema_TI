<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EstadoPedidoMail;
use App\Models\Pedido;
use App\Support\PedidoEstados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    // ── VER DETALLE DEL PEDIDO
    public function show($id)
    {
        $pedido = Pedido::with(['cliente', 'disenio', 'tallas', 'comprobantes', 'historial.administrador'])
                        ->findOrFail($id);

        if (is_null($pedido->visto_admin_at)) {
            $pedido->visto_admin_at = now();
            $pedido->save();
        }

        return view('admin.pedidos.show', compact('pedido'));
    }

    // ── ACTUALIZAR ESTADO
    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        if (!PedidoEstados::pagoVerificado($pedido->estado_pago)) {
            return back()->withErrors(['estado' => 'Debes verificar el comprobante de pago antes de cambiar el estado del pedido.']);
        }

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
        if ($request->filled('tiempo_estimado')) {
            $pedido->tiempo_estimado = $request->tiempo_estimado;
        }
        $pedido->save();

        if ($pedido->cliente?->email) {
            Mail::to($pedido->cliente->email)->send(new EstadoPedidoMail(
                $pedido->cliente->nombre,
                $pedido->codigo,
                'Camiseta personalizada',
                PedidoEstados::label($pedido->estado),
                $pedido->tiempo_estimado,
            ));
        }

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