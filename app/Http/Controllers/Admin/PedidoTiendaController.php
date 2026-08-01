<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EstadoPedidoMail;
use App\Models\ComprobanteMaestro;
use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use App\Support\PedidoEstados;
use App\Support\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PedidoTiendaController extends Controller
{
    // ── LISTA UNIFICADA: maestros + pedidos sueltos de ropa/uniforme/chompa/camiseta personalizada (legado)
    public function index()
    {
        $pedidos = self::pedidosUnificados();

        return view('Admin.pedidos_tienda.index', compact('pedidos'));
    }

    // ── Colección unificada de TODOS los tipos de pedido (usada también por el dashboard)
    public static function pedidosUnificados()
    {
        $maestros = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
                'comprobantes',
            ])
            ->get()
            ->map(function ($p) {
                $nuevo = collect([$p->pedidoUniforme, $p->pedidoChompa, $p->pedidoPlantilla])
                    ->filter()
                    ->contains(fn ($hijo) => is_null($hijo->visto_admin_at));
                return ['tipo' => 'Combinado', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => $nuevo];
            });

        $soloUniformes = PedidoUniforme::with(['cliente', 'items.uniforme', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Uniforme', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $soloChompas = PedidoChompa::with(['cliente', 'items.chompa', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Chompa', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $soloPlantillas = PedidoPlantilla::with(['cliente', 'items.plantilla', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->get()
            ->map(fn ($p) => ['tipo' => 'Ropa', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $camisetas = Pedido::with(['cliente', 'disenio'])
            ->get()
            ->map(fn ($p) => ['tipo' => 'Camiseta', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        return $maestros->concat($soloUniformes)->concat($soloChompas)->concat($soloPlantillas)->concat($camisetas)
            ->sortByDesc('fecha')
            ->values();
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

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if ($hijo && is_null($hijo->visto_admin_at)) {
                $hijo->visto_admin_at = now();
                $hijo->save();
            }
        }

        return view('Admin.pedidos_tienda.show', compact('pedido'));
    }

    // ── CAMBIAR ESTADO DE PRODUCCIÓN GENERAL (un solo control para todo el
    //    pedido combinado: aplica el mismo estado a ropa/uniformes/chompas a
    //    la vez y envía UNA sola notificación, en vez de repetir el cambio
    //    y el aviso por cada sub-pedido por separado).
    public function actualizarEstado(Request $request, $id)
    {
        $pedido = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
            ])
            ->findOrFail($id);

        $hijos = collect([$pedido->pedidoPlantilla, $pedido->pedidoUniforme, $pedido->pedidoChompa])->filter();

        foreach ($hijos as $hijo) {
            if (!PedidoEstados::pagoVerificado($hijo->estado_pago)) {
                return back()->withErrors(['estado' => 'Debes verificar todos los comprobantes de pago antes de cambiar el estado del pedido.']);
            }
        }

        $request->validate([
            'estado' => 'required|in:recibido,en_produccion,listo,enviado,entregado,cancelado',
        ]);

        foreach ($hijos as $hijo) {
            $hijo->estado = $request->estado;
            if ($request->filled('tiempo_estimado')) {
                $hijo->tiempo_estimado = $request->tiempo_estimado;
            }
            $hijo->save();
        }

        if ($request->filled('tiempo_estimado')) {
            $pedido->tiempo_estimado = $request->tiempo_estimado;
            $pedido->save();
        }

        $estadoLabel = PedidoEstados::label($request->estado);

        if ($request->boolean('notificar_email') && $pedido->cliente?->email) {
            $tipos = $hijos->map(fn ($hijo) => match (true) {
                $hijo instanceof PedidoPlantilla => 'Ropa',
                $hijo instanceof PedidoUniforme  => 'Uniforme',
                $hijo instanceof PedidoChompa    => 'Chompa',
                default => null,
            })->filter()->implode(' + ');

            $imagenRelativa = $pedido->pedidoPlantilla?->items->first()?->plantilla?->imagen_preview
                ?? $pedido->pedidoUniforme?->items->first()?->uniforme?->imagen
                ?? $pedido->pedidoChompa?->items->first()?->chompa?->imagen;
            $imagenPath = $imagenRelativa ? Storage::disk('public')->path($imagenRelativa) : null;

            Mail::to($pedido->cliente->email)->send(new EstadoPedidoMail(
                $pedido->cliente->nombre,
                $pedido->codigo,
                $tipos ?: 'Pedido',
                $estadoLabel,
                $pedido->tiempo_estimado,
                $imagenPath,
            ));
        }

        $whatsappUrl = null;
        if ($request->boolean('notificar_whatsapp')) {
            $mensaje = "Hola {$pedido->cliente->nombre}, tu pedido {$pedido->codigo} ahora está: {$estadoLabel}.";
            if ($pedido->tiempo_estimado) {
                $mensaje .= " Tiempo estimado de entrega: {$pedido->tiempo_estimado}.";
            }
            $whatsappUrl = WhatsappHelper::link($pedido->cliente?->telefono, $mensaje);
        }

        return back()->with([
            'success' => 'Estado del pedido actualizado.',
            'whatsapp_url' => $whatsappUrl,
        ]);
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
