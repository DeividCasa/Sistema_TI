<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ComprobantePlantilla;
use App\Models\Plantilla;
use App\Models\PlantillaTalla;
use App\Models\PedidoPlantilla;
use Illuminate\Http\Request;

class CarritoPlantillaController extends Controller
{
    // ── AGREGAR AL CARRITO
    public function agregar(Request $request)
    {
        $request->validate([
            'plantilla_id' => 'required|exists:plantillas,id',
            'talla_id'     => 'required|exists:plantilla_tallas,id',
            'cantidad'     => 'required|integer|min:1|max:100',
        ], [
            'talla_id.required' => 'Debes seleccionar una talla.',
            'cantidad.min'      => 'La cantidad mínima es 1.',
        ]);

        $plantilla = Plantilla::findOrFail($request->plantilla_id);
        $talla = PlantillaTalla::where('plantilla_id', $plantilla->id)
                               ->where('disponible', 1)
                               ->findOrFail($request->talla_id);

        $carrito = session('carrito_plantillas', []);
        $key = $plantilla->id . '-' . $talla->id;

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$key] = [
                'plantilla_id' => $plantilla->id,
                'talla_id'     => $talla->id,
                'nombre'       => $plantilla->nombre,
                'tipo_prenda'  => $plantilla->tipo_prenda,
                'talla'        => $talla->talla,
                'precio'       => (float) $talla->precio,
                'cantidad'     => (int) $request->cantidad,
                'imagen'       => $plantilla->imagen_preview,
            ];
        }

        session(['carrito_plantillas' => $carrito]);

        return redirect()->route('cliente.carrito.index')
                         ->with('success', 'Producto agregado al carrito.');
    }

    // ── ACTUALIZAR CANTIDAD
    public function actualizar(Request $request, $key)
    {
        $request->validate(['cantidad' => 'required|integer|min:1|max:100']);

        $carrito = session('carrito_plantillas', []);
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] = (int) $request->cantidad;
            session(['carrito_plantillas' => $carrito]);
        }

        return redirect()->route('cliente.carrito.index');
    }

    // ── QUITAR ITEM
    public function quitar(Request $request, $key)
    {
        $carrito = session('carrito_plantillas', []);
        unset($carrito[$key]);
        session(['carrito_plantillas' => $carrito]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html'    => view('cliente.componentes.carrito-dropdown')->render(),
                'count'   => count(session('carrito_plantillas', []))
                    + count(session('carrito_uniformes', []))
                    + count(session('carrito_chompas', [])),
            ]);
        }

        return back()->with('success', 'Producto quitado del carrito.');
    }

    // ── VACIAR CARRITO
    public function vaciar()
    {
        session()->forget('carrito_plantillas');
        return redirect()->route('cliente.carrito.index');
    }

    // ── GUARDAR COMPROBANTE
    public function guardarComprobante(Request $request, $id)
    {
        $pedido = PedidoPlantilla::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $reglas = [
            'tipo'       => 'required|in:adelanto,pago_completo,saldo_final',
            'archivo'    => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'referencia' => 'nullable|string|max:100',
        ];
        if (!$pedido->tipo_entrega) {
            $reglas['tipo_entrega']      = 'required|in:retiro,domicilio';
            $reglas['direccion_entrega'] = 'required_if:tipo_entrega,domicilio|nullable|string|max:255';
        }

        $request->validate($reglas, [
            'archivo.required'              => 'Debes subir el comprobante.',
            'archivo.mimes'                 => 'Solo se aceptan imágenes o PDF.',
            'tipo_entrega.required'         => 'Selecciona cómo quieres recibir tu pedido.',
            'direccion_entrega.required_if' => 'Ingresa la dirección donde quieres recibir tu pedido.',
        ]);

        if (!$pedido->tipo_entrega) {
            $pedido->tipo_entrega = $request->tipo_entrega;
            $pedido->direccion_entrega = $request->tipo_entrega === 'domicilio' ? $request->direccion_entrega : null;
        }

        $monto = match ($request->tipo) {
            'adelanto'      => $pedido->precio_adelanto,
            'pago_completo' => $pedido->precio_total,
            'saldo_final'   => $pedido->precio_saldo,
        };

        $archivo = $request->file('archivo')->store('comprobantes_plantilla', 'public');

        ComprobantePlantilla::create([
            'pedido_plantilla_id' => $pedido->id,
            'tipo'                => $request->tipo,
            'archivo'             => $archivo,
            'referencia'          => $request->referencia,
            'monto'               => $monto,
            'estado'              => 'pendiente',
        ]);

        $pedido->estado_pago = match ($request->tipo) {
            'adelanto'      => 'adelanto_enviado',
            'pago_completo' => 'pago_completo_enviado',
            'saldo_final'   => 'saldo_enviado',
        };
        $pedido->save();

        return redirect()->route('cliente.mis-pedidos')
                         ->with('success', '¡Comprobante enviado! El administrador lo verificará pronto.');
    }
}
