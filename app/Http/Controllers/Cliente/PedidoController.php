<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Disenio;
use App\Models\Plantilla;
use App\Models\TallaPedido;
use App\Models\ComprobantePago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    // ── CREAR PEDIDO (desde la página de producto)
    public function store(Request $request)
    {
        $request->validate([
            'plantilla_id' => 'required|exists:plantillas,id',
            'talla'        => 'required|string',
            'color'        => 'nullable|string',
            'cantidad'     => 'required|integer|min:1',
        ]);

        $plantilla = Plantilla::findOrFail($request->plantilla_id);
        $clienteId = session('usuario_id');

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
        $precioTotal    = $plantilla->precio * $request->cantidad;
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

        return redirect()->route('cliente.pedidos.comprobante', $pedido->id)
                         ->with('success', '¡Pedido creado! Ahora sube tu comprobante de pago.');
    }

    // ── MOSTRAR FORMULARIO DE COMPROBANTE
    public function comprobante($id)
    {
        $pedido = Pedido::with(['disenio.plantilla', 'tallas'])
                        ->where('cliente_id', session('usuario_id'))
                        ->findOrFail($id);

        return view('cliente.comprobante', compact('pedido'));
    }

    // ── GUARDAR COMPROBANTE
    public function guardarComprobante(Request $request, $id)
    {
        $pedido = Pedido::where('cliente_id', session('usuario_id'))->findOrFail($id);

        $request->validate([
            'archivo'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'referencia' => 'nullable|string|max:100',
        ], [
            'archivo.required' => 'Debes subir el comprobante.',
            'archivo.mimes'    => 'Solo se aceptan imágenes o PDF.',
            'archivo.max'      => 'El archivo no debe superar 4MB.',
        ]);

        $rutaArchivo = $request->file('archivo')->store('comprobantes', 'public');

        ComprobantePago::create([
            'pedido_id'  => $pedido->id,
            'tipo'       => 'adelanto',
            'archivo'    => $rutaArchivo,
            'referencia' => $request->referencia,
            'monto'      => $pedido->precio_adelanto,
            'estado'     => 'pendiente',
        ]);

        $pedido->estado_pago = 'adelanto_enviado';
        $pedido->save();

        return redirect()->route('cliente.pedidos.index')
                         ->with('success', '¡Comprobante enviado! El administrador lo verificará pronto.');
    }

    // ── LISTA "MIS PEDIDOS"
    public function index()
    {
        $pedidos = Pedido::with(['disenio.plantilla', 'comprobantes'])
                         ->where('cliente_id', session('usuario_id'))
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }
}