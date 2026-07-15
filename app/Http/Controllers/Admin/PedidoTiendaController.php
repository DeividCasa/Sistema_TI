<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComprobanteMaestro;
use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use Illuminate\Http\Request;

class PedidoTiendaController extends Controller
{
    // ── LISTA UNIFICADA: maestros + pedidos sueltos de ropa/uniforme/chompa/camiseta personalizada (legado)
    public function index()
    {
        $maestros = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
                'comprobantes',
            ])
            ->get()
            ->map(fn ($p) => ['tipo' => 'Combinado', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloUniformes = PedidoUniforme::with(['cliente', 'items.uniforme', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Uniforme', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloChompas = PedidoChompa::with(['cliente', 'items.chompa', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Chompa', 'pedido' => $p, 'fecha' => $p->created_at]);

        $soloPlantillas = PedidoPlantilla::with(['cliente', 'items.plantilla', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Ropa', 'pedido' => $p, 'fecha' => $p->created_at]);

        $camisetas = Pedido::with(['cliente', 'disenio'])
            ->get()
            ->map(fn ($p) => ['tipo' => 'Camiseta', 'pedido' => $p, 'fecha' => $p->created_at]);

        $pedidos = $maestros->concat($soloUniformes)->concat($soloChompas)->concat($soloPlantillas)->concat($camisetas)
            ->sortByDesc('fecha')
            ->values();

        return view('Admin.pedidos_tienda.index', compact('pedidos'));
    }

    // ── DETALLE DE UN PEDIDO COMBINADO
    public function show($id)
    {
        $pedido = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
                'comprobantes',
            ])
            ->findOrFail($id);

        return view('Admin.pedidos_tienda.show', compact('pedido'));
    }

    // ── MARCAR PAGO COMO COMPLETADO (override manual del admin), cascada a hijos
    public function marcarPagoCompleto($id)
    {
        $pedido = PedidoMaestro::findOrFail($id);
        $pedido->estado_pago = 'pagado_completo';
        $pedido->save();

        $pedido->pedidoUniforme?->update(['estado_pago' => 'pagado_completo']);
        $pedido->pedidoChompa?->update(['estado_pago' => 'pagado_completo']);
        $pedido->pedidoPlantilla?->update(['estado_pago' => 'pagado_completo']);

        return back()->with('success', 'Pago marcado como completado.');
    }

    // ── VERIFICAR COMPROBANTE COMBINADO
    public function verificarComprobante($id)
    {
        $comprobante = ComprobanteMaestro::with('pedido')->findOrFail($id);
        $comprobante->estado = 'verificado';
        $comprobante->save();

        $pedido = $comprobante->pedido;

        $nuevoEstadoPago = $comprobante->tipo === 'adelanto' ? 'adelanto_verificado' : 'pagado_completo';
        $pedido->estado_pago = $nuevoEstadoPago;
        $pedido->save();

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if (!$hijo) {
                continue;
            }
            $hijo->estado_pago = $nuevoEstadoPago;
            if ($hijo->estado === 'recibido') {
                $hijo->estado = 'en_produccion';
            }
            $hijo->save();
        }

        return back()->with('success', 'Comprobante verificado correctamente.');
    }

    // ── RECHAZAR COMPROBANTE COMBINADO
    public function rechazarComprobante(Request $request, $id)
    {
        $comprobante = ComprobanteMaestro::with('pedido')->findOrFail($id);
        $comprobante->estado = 'rechazado';
        $comprobante->nota_admin = $request->nota_admin ?? 'Comprobante no válido.';
        $comprobante->save();

        $pedido = $comprobante->pedido;
        $nuevoEstadoPago = $comprobante->tipo === 'saldo_final' ? 'adelanto_verificado' : 'pendiente';
        $pedido->estado_pago = $nuevoEstadoPago;
        $pedido->save();

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if (!$hijo) {
                continue;
            }
            $hijo->estado_pago = $nuevoEstadoPago;
            $hijo->save();
        }

        return back()->with('success', 'Comprobante rechazado.');
    }
}
