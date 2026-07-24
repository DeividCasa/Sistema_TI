<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Uniforme;
use App\Models\UniformeTalla;
use App\Models\PedidoUniforme;
use App\Models\ComprobanteUniforme;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CarritoUniformeController extends Controller
{
    public function index()
    {
        $carrito = session('carrito_uniformes', []);
        $carritoChompas = session('carrito_chompas', []);
        $carritoPlantillas = session('carrito_plantillas', []);

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        $adelanto = round($total / 2, 2);
        $saldo = $total - $adelanto;

        $totalChompas = 0;
        foreach ($carritoChompas as $item) {
            $totalChompas += $item['precio'] * $item['cantidad'];
        }

        $totalPlantillas = 0;
        foreach ($carritoPlantillas as $item) {
            $totalPlantillas += $item['precio'] * $item['cantidad'];
        }

        $tiposConItems = collect([
            !empty($carrito) ? 'uniforme' : null,
            !empty($carritoChompas) ? 'chompa' : null,
            !empty($carritoPlantillas) ? 'plantilla' : null,
        ])->filter()->values();
        $hayAmbos = $tiposConItems->count() > 1;

        $totalCombinado = $total + $totalChompas + $totalPlantillas;
        $adelantoCombinado = $adelanto + round($totalChompas / 2, 2) + round($totalPlantillas / 2, 2);
        $saldoCombinado = $totalCombinado - $adelantoCombinado;

        return view('cliente.carrito', compact(
            'carrito', 'total', 'adelanto', 'saldo',
            'carritoChompas', 'totalChompas',
            'carritoPlantillas', 'totalPlantillas',
            'hayAmbos', 'totalCombinado', 'adelantoCombinado', 'saldoCombinado'
        ));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'uniforme_id' => 'required|exists:uniformes,id',
            'talla_id'    => 'required|exists:uniforme_tallas,id',
            'cantidad'    => 'required|integer|min:1|max:100',
        ], [
            'talla_id.required' => 'Debes seleccionar una talla.',
            'cantidad.min'      => 'La cantidad mínima es 1.',
        ]);

        $uniforme = Uniforme::findOrFail($request->uniforme_id);

        $talla = UniformeTalla::where('uniforme_id', $uniforme->id)
            ->where('disponible', 1)
            ->findOrFail($request->talla_id);

        $carrito = session('carrito_uniformes', []);
        $key = $uniforme->id . '-' . $talla->id;

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$key] = [
                'uniforme_id' => $uniforme->id,
                'talla_id'    => $talla->id,
                'nombre'      => $uniforme->nombre,
                'tipo_tela'   => $uniforme->tipo_tela,
                'talla'       => $talla->talla,
                'precio'      => (float) $talla->precio,
                'cantidad'    => (int) $request->cantidad,
                'imagen'      => $uniforme->imagen,
            ];
        }

        session(['carrito_uniformes' => $carrito]);

        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Uniforme agregado al carrito.');
    }

    public function actualizar(Request $request, $key)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:100'
        ]);

        $carrito = session('carrito_uniformes', []);

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] = (int) $request->cantidad;
            session(['carrito_uniformes' => $carrito]);
        }

        return redirect()->route('cliente.carrito.index');
    }

    public function quitar(Request $request, $key)
    {
        $carrito = session('carrito_uniformes', []);

        unset($carrito[$key]);

        session(['carrito_uniformes' => $carrito]);

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

    public function vaciar()
    {
        session()->forget('carrito_uniformes');

        return redirect()->route('cliente.carrito.index');
    }

    public function confirmar(CheckoutService $checkout)
    {
        if (empty(session('carrito_uniformes', [])) && empty(session('carrito_chompas', [])) && empty(session('carrito_plantillas', []))) {
            return redirect()->route('cliente.catalogo.index')
                ->with('success', 'Tu carrito está vacío.');
        }

        $checkout->confirmar(session('usuario_id'));

        return redirect()->route('cliente.mis-pedidos')
            ->with('success', '¡Pedido creado! Ahora realiza el pago.');
    }

    public function guardarComprobante(Request $request, $id)
    {
        $pedido = PedidoUniforme::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $request->validate([
            'tipo'       => 'required|in:adelanto,pago_completo,saldo_final',
            'archivo'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'referencia' => 'nullable|string|max:100',
        ]);

        $monto = match ($request->tipo) {
            'adelanto'      => $pedido->precio_adelanto,
            'pago_completo' => $pedido->precio_total,
            'saldo_final'   => $pedido->precio_saldo,
        };

        $rutaArchivo = $request->file('archivo')->store('comprobantes_uniformes', 'public');

        ComprobanteUniforme::create([
            'pedido_uniforme_id' => $pedido->id,
            'tipo'               => $request->tipo,
            'archivo'            => $rutaArchivo,
            'referencia'         => $request->referencia,
            'monto'              => $monto,
            'estado'             => 'pendiente',
        ]);

        $pedido->estado_pago = match ($request->tipo) {
            'adelanto'      => 'adelanto_enviado',
            'pago_completo' => 'pago_completo_enviado',
            'saldo_final'   => 'saldo_enviado',
        };

        $pedido->save();

        return redirect()->route('cliente.mis-pedidos')
            ->with('success', '¡Comprobante enviado!');
    }
}