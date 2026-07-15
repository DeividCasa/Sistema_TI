<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Chompa;
use App\Models\ChompaTalla;
use App\Models\PedidoChompa;
use App\Models\ComprobanteChompa;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CarritoChompaController extends Controller
{
    // ── VER CARRITO
    public function index()
    {
        $carrito = session('carrito_chompas', []);
        $carritoUniformes = session('carrito_uniformes', []);
        $carritoPlantillas = session('carrito_plantillas', []);

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        $adelanto = round($total / 2, 2);
        $saldo    = $total - $adelanto;

        $totalUniformes = 0;
        foreach ($carritoUniformes as $item) {
            $totalUniformes += $item['precio'] * $item['cantidad'];
        }

        $totalPlantillas = 0;
        foreach ($carritoPlantillas as $item) {
            $totalPlantillas += $item['precio'] * $item['cantidad'];
        }

        $tiposConItems = collect([
            !empty($carrito) ? 'chompa' : null,
            !empty($carritoUniformes) ? 'uniforme' : null,
            !empty($carritoPlantillas) ? 'plantilla' : null,
        ])->filter()->values();
        $hayAmbos = $tiposConItems->count() > 1;

        $totalCombinado = $total + $totalUniformes + $totalPlantillas;
        $adelantoCombinado = $adelanto + round($totalUniformes / 2, 2) + round($totalPlantillas / 2, 2);
        $saldoCombinado = $totalCombinado - $adelantoCombinado;

        return view('cliente.chompas.carrito', compact(
            'carrito', 'total', 'adelanto', 'saldo',
            'carritoUniformes', 'totalUniformes',
            'carritoPlantillas', 'totalPlantillas',
            'hayAmbos', 'totalCombinado', 'adelantoCombinado', 'saldoCombinado'
        ));
    }

    // ── AGREGAR AL CARRITO
    public function agregar(Request $request)
    {
        $request->validate([
            'chompa_id' => 'required|exists:chompas,id',
            'talla_id'  => 'required|exists:chompa_tallas,id',
            'cantidad'  => 'required|integer|min:1|max:100',
        ], [
            'talla_id.required' => 'Debes seleccionar una talla.',
            'cantidad.min'      => 'La cantidad mínima es 1.',
        ]);

        $chompa = Chompa::findOrFail($request->chompa_id);
        $talla  = ChompaTalla::where('chompa_id', $chompa->id)
                             ->where('disponible', 1)
                             ->findOrFail($request->talla_id);

        $carrito = session('carrito_chompas', []);
        $key     = $chompa->id . '-' . $talla->id;

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$key] = [
                'chompa_id' => $chompa->id,
                'talla_id'  => $talla->id,
                'nombre'    => $chompa->nombre,
                'tipo_tela' => $chompa->tipo_tela,
                'talla'     => $talla->talla,
                'precio'    => (float) $talla->precio,
                'cantidad'  => (int) $request->cantidad,
                'imagen'    => $chompa->imagen,
            ];
        }

        session(['carrito_chompas' => $carrito]);

        return redirect()->route('cliente.chompas.carrito')
                         ->with('success', 'Chompa agregada al carrito.');
    }

    // ── ACTUALIZAR CANTIDAD
    public function actualizar(Request $request, $key)
    {
        $request->validate(['cantidad' => 'required|integer|min:1|max:100']);

        $carrito = session('carrito_chompas', []);
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] = (int) $request->cantidad;
            session(['carrito_chompas' => $carrito]);
        }

        return redirect()->route('cliente.chompas.carrito');
    }

    // ── QUITAR ITEM
    public function quitar(Request $request, $key)
    {
        $carrito = session('carrito_chompas', []);
        unset($carrito[$key]);
        session(['carrito_chompas' => $carrito]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html'    => view('cliente.componentes.carrito-dropdown')->render(),
                'count'   => count(session('carrito_uniformes', []))
                    + count(session('carrito_chompas', []))
                    + count(session('carrito_plantillas', [])),
            ]);
        }

        return back()->with('success', 'Producto quitado del carrito.');
    }

    // ── VACIAR CARRITO
    public function vaciar()
    {
        session()->forget('carrito_chompas');
        return redirect()->route('cliente.chompas.carrito');
    }

    // ── CONFIRMAR PEDIDO
    public function confirmar(CheckoutService $checkout)
    {
        if (empty(session('carrito_uniformes', [])) && empty(session('carrito_chompas', [])) && empty(session('carrito_plantillas', []))) {
            return redirect()->route('cliente.catalogo.index')
                             ->with('success', 'Tu carrito está vacío.');
        }

        $resultado = $checkout->confirmar(session('usuario_id'));

        if ($resultado['maestro']) {
            return redirect()->route('cliente.pedido-maestro.pago', $resultado['maestro']->id)
                             ->with('success', '¡Pedido registrado! Ahora sube tu comprobante de adelanto.');
        }

        if ($resultado['pedidoChompa']) {
            return redirect()->route('cliente.chompas.pago', $resultado['pedidoChompa']->id)
                             ->with('success', '¡Pedido registrado! Ahora sube tu comprobante de adelanto.');
        }

        if ($resultado['pedidoUniforme']) {
            return redirect()->route('cliente.uniformes.pago', $resultado['pedidoUniforme']->id)
                             ->with('success', '¡Pedido registrado! Ahora sube tu comprobante de adelanto.');
        }

        return redirect()->route('cliente.plantillas.pago', $resultado['pedidoPlantilla']->id)
                         ->with('success', '¡Pedido registrado! Ahora sube tu comprobante de adelanto.');
    }

    // ── PÁGINA DE PAGO (subir comprobante)
    public function pago($id)
    {
        $pedido = PedidoChompa::with(['items.chompa', 'comprobantes'])
                              ->where('cliente_id', session('usuario_id'))
                              ->findOrFail($id);

        return view('cliente.chompas.pago', compact('pedido'));
    }

    // ── GUARDAR COMPROBANTE
    public function guardarComprobante(Request $request, $id)
    {
        $request->validate([
            'comprobante' => 'required|image|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'referencia'  => 'nullable|string|max:100',
            'monto'       => 'nullable|numeric|min:0',
        ], [
            'comprobante.required' => 'Debes adjuntar el comprobante de pago.',
            'comprobante.image'    => 'El archivo debe ser imagen o PDF.',
        ]);

        $pedido = PedidoChompa::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $archivo = $request->file('comprobante')->store('comprobantes_chompa', 'public');

        ComprobanteChompa::create([
            'pedido_chompa_id' => $pedido->id,
            'tipo'             => 'adelanto',
            'archivo'          => $archivo,
            'referencia'       => $request->referencia,
            'monto'            => $request->monto ?? $pedido->precio_adelanto,
            'estado'           => 'pendiente',
        ]);

        return redirect()->route('cliente.chompas.mis-pedidos')
                         ->with('success', 'Comprobante enviado. En breve será verificado.');
    }

    // ── MIS PEDIDOS DE CHOMPAS
    public function misPedidos()
    {
        $pedidos = PedidoChompa::with(['items.chompa', 'comprobantes'])
                               ->where('cliente_id', session('usuario_id'))
                               ->whereNull('pedido_maestro_id')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('cliente.chompas.mis_pedidos', compact('pedidos'));
    }
}
