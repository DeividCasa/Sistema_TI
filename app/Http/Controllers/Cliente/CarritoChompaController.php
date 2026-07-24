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

        $checkout->confirmar(session('usuario_id'));

        return redirect()->route('cliente.mis-pedidos')
                         ->with('success', '¡Pedido registrado! Ahora sube tu comprobante de adelanto.');
    }

    // ── GUARDAR COMPROBANTE
    public function guardarComprobante(Request $request, $id)
    {
        $pedido = PedidoChompa::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $request->validate([
            'tipo'       => 'required|in:adelanto,pago_completo,saldo_final',
            'archivo'    => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'referencia' => 'nullable|string|max:100',
        ], [
            'archivo.required' => 'Debes adjuntar el comprobante de pago.',
            'archivo.mimes'    => 'El archivo debe ser imagen o PDF.',
        ]);

        $monto = match ($request->tipo) {
            'adelanto'      => $pedido->precio_adelanto,
            'pago_completo' => $pedido->precio_total,
            'saldo_final'   => $pedido->precio_saldo,
        };

        $archivo = $request->file('archivo')->store('comprobantes_chompa', 'public');

        ComprobanteChompa::create([
            'pedido_chompa_id' => $pedido->id,
            'tipo'             => $request->tipo,
            'archivo'          => $archivo,
            'referencia'       => $request->referencia,
            'monto'            => $monto,
            'estado'           => 'pendiente',
        ]);

        $pedido->estado_pago = match ($request->tipo) {
            'adelanto'      => 'adelanto_enviado',
            'pago_completo' => 'pago_completo_enviado',
            'saldo_final'   => 'saldo_enviado',
        };
        $pedido->save();

        return redirect()->route('cliente.mis-pedidos')
                         ->with('success', 'Comprobante enviado. En breve será verificado.');
    }
}
