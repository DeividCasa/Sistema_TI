<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ComprobantePlantilla;
use App\Models\Plantilla;
use App\Models\PedidoPlantilla;
use Illuminate\Http\Request;

class CarritoPlantillaController extends Controller
{
    // ── AGREGAR AL CARRITO
    public function agregar(Request $request)
    {
        $request->validate([
            'plantilla_id' => 'required|exists:plantillas,id',
            'talla'        => 'nullable|string',
            'color'        => 'nullable|string',
            'cantidad'     => 'required|integer|min:1|max:100',
        ]);

        $plantilla = Plantilla::findOrFail($request->plantilla_id);

        $talla = $request->talla;
        if (empty($talla) && !empty($plantilla->tallas)) {
            $talla = $plantilla->tallas[0];
        }

        $color = $request->color;
        if (empty($color) && !empty($plantilla->colores)) {
            $color = $plantilla->colores[0];
        }

        $carrito = session('carrito_plantillas', []);
        // El color viene como hex (#2563EB); el '#' rompe las URLs generadas por
        // route() (Laravel no lo escapa y el navegador lo trata como fragmento),
        // así que para la key usamos el hex sin el símbolo.
        $colorKey = $color ? ltrim($color, '#') : 'sin-color';
        $key = $plantilla->id . '-' . ($talla ?: 'sin-talla') . '-' . $colorKey;

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$key] = [
                'plantilla_id' => $plantilla->id,
                'nombre'       => $plantilla->nombre,
                'tipo_prenda'  => $plantilla->tipo_prenda,
                'talla'        => $talla,
                'color'        => $color,
                'precio'       => (float) $plantilla->precio,
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

        $request->validate([
            'tipo'       => 'required|in:adelanto,pago_completo,saldo_final',
            'archivo'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'referencia' => 'nullable|string|max:100',
        ], [
            'archivo.required' => 'Debes subir el comprobante.',
            'archivo.mimes'    => 'Solo se aceptan imágenes o PDF.',
        ]);

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
