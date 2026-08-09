<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Disenio;
use App\Models\Plantilla;
use App\Models\TallaPedido;
use App\Models\ComprobantePago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    // ── CREAR PEDIDO (desde la página de producto)
    public function store(Request $request)
    {
        $request->validate([
            'plantilla_id' => 'required|exists:plantillas,id',
            'talla'        => 'required|string|max:10',
            'color'        => 'nullable|string|max:40',
            'cantidad'     => 'required|integer|min:1|max:100',
        ]);

        $plantilla = Plantilla::findOrFail($request->plantilla_id);
        $clienteId = session('usuario_id');

        $pedido = DB::transaction(function () use ($request, $plantilla, $clienteId) {
            // 1. Crear el diseño base
            $disenio = Disenio::create([
                'cliente_id'   => $clienteId,
                'plantilla_id' => $plantilla->id,
                'nombre'       => $plantilla->nombre,
                'configuracion' => [
                    'color' => $request->color,
                    'talla' => $request->talla,
                ],
                'origen' => 'plantilla',
            ]);

            // 2. Calcular precios
            $precioTalla    = $plantilla->tallas()->where('disponible', 1)->where('talla', $request->talla)->value('precio')
                            ?? $plantilla->tallas()->where('disponible', 1)->min('precio')
                            ?? 0;
            $precioTotal    = $precioTalla * $request->cantidad;
            $precioAdelanto = round($precioTotal / 2, 2);
            $precioSaldo    = $precioTotal - $precioAdelanto;

            // 3. Generar código único
            $codigo = 'LJ-' . date('Y') . '-' . str_pad(Pedido::count() + 1, 3, '0', STR_PAD_LEFT);

            // 4. Crear el pedido
            $pedido = Pedido::create([
                'cliente_id'      => $clienteId,
                'disenio_id'      => $disenio->id,
                'codigo'          => $codigo,
                'cantidad_total'  => $request->cantidad,
                'precio_total'    => $precioTotal,
                'precio_adelanto' => $precioAdelanto,
                'precio_saldo'    => $precioSaldo,
                'estado'          => 'recibido',
                'estado_pago'     => 'pendiente',
            ]);

            // 5. Registrar la talla
            TallaPedido::create([
                'pedido_id' => $pedido->id,
                'talla'     => $request->talla,
                'cantidad'  => $request->cantidad,
            ]);

            return $pedido;
        });

        return redirect()->route('cliente.pedidos.comprobante', $pedido->id)
                         ->with('success', '¡Pedido creado! Ahora sube tu comprobante de pago.');
    }

    // ── MOSTRAR FORMULARIO DE COMPROBANTE
    public function comprobante($id)
    {
        $pedido = Pedido::with(['disenio.plantilla', 'tallas', 'comprobantes'])
                        ->where('cliente_id', session('usuario_id'))
                        ->findOrFail($id);

        $info = \App\Models\InformacionLocal::actual();

        return view('cliente.comprobante', compact('pedido', 'info'));
    }

    // ── GUARDAR COMPROBANTE
    public function guardarComprobante(Request $request, $id)
    {
        $pedido = Pedido::where('cliente_id', session('usuario_id'))->findOrFail($id);

        // Bloquea reenvíos mientras un comprobante ya está pendiente de revisión
        // o el pedido ya quedó pagado por completo. "adelanto_verificado" SÍ debe
        // dejar pasar: ahí es cuando corresponde subir el comprobante del saldo.
        if (in_array($pedido->estado_pago, ['adelanto_enviado', 'pago_completo_enviado', 'saldo_enviado', 'pagado_completo'])) {
            return redirect()->route('cliente.pedidos.comprobante', $pedido->id);
        }

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
            'archivo.max'                    => 'El archivo no debe superar 5MB.',
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

        $rutaArchivo = $request->file('archivo')->store('comprobantes', 'public');

        ComprobantePago::create([
            'pedido_id'  => $pedido->id,
            'tipo'       => $request->tipo,
            'archivo'    => $rutaArchivo,
            'referencia' => $request->referencia,
            'monto'      => $monto,
            'estado'     => 'pendiente',
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