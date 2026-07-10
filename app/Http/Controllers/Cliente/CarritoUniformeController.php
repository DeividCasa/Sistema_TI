<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Uniforme;
use App\Models\UniformeTalla;
use App\Models\PedidoUniforme;
use App\Models\PedidoUniformeItem;
use App\Models\ComprobanteUniforme;
use Illuminate\Http\Request;

class CarritoUniformeController extends Controller
{
    public function index()
    {
        $carrito = session('carrito_uniformes', []);

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $adelanto = round($total / 2, 2);
        $saldo = $total - $adelanto;

        return view('cliente.carrito', compact('carrito', 'total', 'adelanto', 'saldo'));
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

    public function quitar($key)
    {
        $carrito = session('carrito_uniformes', []);

        unset($carrito[$key]);

        session(['carrito_uniformes' => $carrito]);

        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Producto quitado del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito_uniformes');

        return redirect()->route('cliente.carrito.index');
    }

    public function confirmar()
    {
        $carrito = session('carrito_uniformes', []);

        if (empty($carrito)) {
            return redirect()->route('cliente.uniformes.index')
                ->with('success', 'Tu carrito está vacío.');
        }

        $clienteId = session('usuario_id');

        $total = 0;
        $cantidadTotal = 0;

        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
            $cantidadTotal += $item['cantidad'];
        }

        $adelanto = round($total / 2, 2);
        $saldo = $total - $adelanto;

        $codigo = 'UE-' . date('Y') . '-' . str_pad(PedidoUniforme::count() + 1, 3, '0', STR_PAD_LEFT);

        $pedido = PedidoUniforme::create([
            'cliente_id'      => $clienteId,
            'codigo'          => $codigo,
            'cantidad_total'  => $cantidadTotal,
            'precio_total'    => $total,
            'precio_adelanto' => $adelanto,
            'precio_saldo'    => $saldo,
            'estado'          => 'recibido',
            'estado_pago'     => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            PedidoUniformeItem::create([
                'pedido_uniforme_id' => $pedido->id,
                'uniforme_id'        => $item['uniforme_id'],
                'uniforme_talla_id'  => $item['talla_id'],
                'talla'              => $item['talla'],
                'precio_unitario'    => $item['precio'],
                'cantidad'           => $item['cantidad'],
                'subtotal'           => $item['precio'] * $item['cantidad'],
            ]);
        }

        session()->forget('carrito_uniformes');

        return redirect()->route('cliente.uniformes.pago', $pedido->id)
            ->with('success', '¡Pedido creado! Ahora realiza el pago.');
    }

    public function pago($id)
    {
        $pedido = PedidoUniforme::with(['items.uniforme', 'comprobantes'])
            ->where('cliente_id', session('usuario_id'))
            ->findOrFail($id);

        return view('cliente.pago', compact('pedido'));
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

        return redirect()->route('cliente.uniformes.mis-pedidos')
            ->with('success', '¡Comprobante enviado!');
    }

    public function misPedidos()
    {
        $pedidos = PedidoUniforme::with(['items.uniforme', 'comprobantes'])
            ->where('cliente_id', session('usuario_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.mis_pedidos', compact('pedidos'));
    }
}