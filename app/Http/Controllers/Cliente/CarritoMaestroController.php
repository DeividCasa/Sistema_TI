<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ComprobanteMaestro;
use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use Illuminate\Http\Request;

class CarritoMaestroController extends Controller
{
    // ── PÁGINA DE PAGO (subir comprobante) — sirve para cualquier tipo de pedido suelto o combinado
    public function pago($tipo, $id)
    {
        $clienteId = session('usuario_id');

        $pedido = match ($tipo) {
            'maestro'  => PedidoMaestro::with(['pedidoPlantilla.items.plantilla', 'pedidoUniforme.items.uniforme', 'pedidoChompa.items.chompa', 'comprobantes'])
                ->where('cliente_id', $clienteId)->findOrFail($id),
            'uniforme' => PedidoUniforme::with(['items.uniforme', 'comprobantes'])
                ->where('cliente_id', $clienteId)->findOrFail($id),
            'chompa'   => PedidoChompa::with(['items.chompa', 'comprobantes'])
                ->where('cliente_id', $clienteId)->findOrFail($id),
            'ropa'     => PedidoPlantilla::with(['items.plantilla', 'comprobantes'])
                ->where('cliente_id', $clienteId)->findOrFail($id),
            default    => abort(404),
        };

        $rutaComprobante = match ($tipo) {
            'maestro'  => route('cliente.pedido-maestro.comprobante', $pedido->id),
            'uniforme' => route('cliente.uniformes.comprobante', $pedido->id),
            'chompa'   => route('cliente.chompas.comprobante', $pedido->id),
            'ropa'     => route('cliente.plantillas.comprobante', $pedido->id),
        };

        return view('cliente.pedido_maestro.pago', compact('pedido', 'tipo', 'rutaComprobante'));
    }

    // ── GUARDAR COMPROBANTE COMBINADO
    public function guardarComprobante(Request $request, $id)
    {
        $request->validate([
            'tipo'        => 'required|in:adelanto,pago_completo,saldo_final',
            'archivo'     => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'referencia'  => 'nullable|string|max:100',
        ], [
            'archivo.required' => 'Debes adjuntar el comprobante de pago.',
        ]);

        $pedido = PedidoMaestro::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $monto = match ($request->tipo) {
            'adelanto'      => $pedido->precio_adelanto,
            'pago_completo' => $pedido->precio_total,
            'saldo_final'   => $pedido->precio_saldo,
        };

        $archivo = $request->file('archivo')->store('comprobantes_maestro', 'public');

        ComprobanteMaestro::create([
            'pedido_maestro_id' => $pedido->id,
            'tipo'              => $request->tipo,
            'archivo'           => $archivo,
            'referencia'        => $request->referencia,
            'monto'             => $monto,
            'estado'            => 'pendiente',
        ]);

        $pedido->estado_pago = match ($request->tipo) {
            'adelanto'      => 'adelanto_enviado',
            'pago_completo' => 'pago_completo_enviado',
            'saldo_final'   => 'saldo_enviado',
        };
        $pedido->save();

        return redirect()->route('cliente.mis-pedidos')
            ->with('success', '¡Comprobante enviado! En breve será verificado.');
    }

    // ── MIS PEDIDOS: combina maestros + pedidos sueltos de uniforme/chompa/camiseta personalizada
    public function misPedidos()
    {
        $clienteId = session('usuario_id');

        $maestros = PedidoMaestro::with(['pedidoPlantilla.items.plantilla', 'pedidoUniforme.items.uniforme', 'pedidoChompa.items.chompa', 'comprobantes'])
            ->where('cliente_id', $clienteId)
            ->get()
            ->map(fn ($p) => ['tipo' => 'maestro', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloUniformes = PedidoUniforme::with(['items.uniforme', 'comprobantes'])
            ->where('cliente_id', $clienteId)
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'uniforme', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloChompas = PedidoChompa::with(['items.chompa', 'comprobantes'])
            ->where('cliente_id', $clienteId)
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'chompa', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloPlantillas = PedidoPlantilla::with(['items.plantilla', 'comprobantes'])
            ->where('cliente_id', $clienteId)
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'ropa', 'pedido' => $p, 'fecha' => $p->created_at]);

        $camisetas = Pedido::with(['disenio.plantilla', 'comprobantes'])
            ->where('cliente_id', $clienteId)
            ->get()
            ->map(fn ($p) => ['tipo' => 'camiseta', 'pedido' => $p, 'fecha' => $p->created_at]);

        $pedidos = $maestros->concat($soloUniformes)->concat($soloChompas)->concat($soloPlantillas)->concat($camisetas)
            ->sortByDesc('fecha')
            ->values();

        return view('cliente.pedido_maestro.mis_pedidos', compact('pedidos'));
    }
}
