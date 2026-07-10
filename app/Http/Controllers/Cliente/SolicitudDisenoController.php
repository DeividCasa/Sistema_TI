<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Disenio;
use App\Models\Pedido;
use App\Models\SolicitudDiseno;
use App\Models\TallaPedido;
use App\Models\TallaSolicitud;
use Illuminate\Http\Request;

class SolicitudDisenoController extends Controller
{
    const TELAS = ['Algodón', 'Poliéster', 'Dry Fit', 'Piqué'];

    // ── "MIS DISEÑOS": lista los diseños guardados por el cliente
    public function index()
    {
        $disenios = Disenio::with(['solicitudes' => function ($q) {
                $q->latest();
            }])
            ->where('cliente_id', session('usuario_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.mis-disenios.index', compact('disenios'));
    }

    // ── FORMULARIO DE COTIZACIÓN PARA UN DISEÑO
    public function create($disenioId)
    {
        $disenio = Disenio::where('cliente_id', session('usuario_id'))->findOrFail($disenioId);
        $telas = self::TELAS;

        return view('cliente.solicitudes.create', compact('disenio', 'telas'));
    }

    // ── GUARDAR LA SOLICITUD DE COTIZACIÓN
    public function store(Request $request, $disenioId)
    {
        $disenio = Disenio::where('cliente_id', session('usuario_id'))->findOrFail($disenioId);

        $request->validate([
            'tela'              => 'required|string|max:60',
            'descripcion'       => 'nullable|string|max:1000',
            'tallas'            => 'required|array|min:1',
            'tallas.*.talla'    => 'required|string|max:10',
            'tallas.*.cantidad' => 'required|integer|min:1',
        ]);

        $solicitud = SolicitudDiseno::create([
            'cliente_id'  => session('usuario_id'),
            'disenio_id'  => $disenio->id,
            'tela'        => $request->tela,
            'descripcion' => $request->descripcion,
            'estado'      => 'pendiente',
        ]);

        foreach ($request->tallas as $fila) {
            TallaSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'talla'        => $fila['talla'],
                'cantidad'     => $fila['cantidad'],
            ]);
        }

        return redirect()->route('cliente.disenios.index')
                         ->with('success', '¡Tu solicitud fue enviada! Un asesor te contactará pronto con el precio.');
    }

    // ── ACEPTAR LA COTIZACIÓN: genera el pedido formal (mismo flujo de comprobante/adelanto)
    public function aceptar($solicitudId)
    {
        $solicitud = SolicitudDiseno::with('tallas')
                        ->where('cliente_id', session('usuario_id'))
                        ->findOrFail($solicitudId);

        if ($solicitud->estado !== 'cotizado') {
            return back()->with('error', 'Esta solicitud todavía no tiene una cotización.');
        }

        $cantidadTotal  = $solicitud->tallas->sum('cantidad');
        $precioTotal    = $solicitud->precio;
        $precioAdelanto = round($precioTotal / 2, 2);
        $precioSaldo    = $precioTotal - $precioAdelanto;
        $codigo         = 'LJ-' . date('Y') . '-' . str_pad(Pedido::count() + 1, 3, '0', STR_PAD_LEFT);

        $pedido = Pedido::create([
            'cliente_id'      => $solicitud->cliente_id,
            'disenio_id'      => $solicitud->disenio_id,
            'codigo'          => $codigo,
            'cantidad_total'  => $cantidadTotal,
            'precio_total'    => $precioTotal,
            'precio_adelanto' => $precioAdelanto,
            'precio_saldo'    => $precioSaldo,
            'estado'          => 'recibido',
            'estado_pago'     => 'pendiente',
            'observaciones'   => $solicitud->descripcion,
        ]);

        foreach ($solicitud->tallas as $tallaSolicitud) {
            TallaPedido::create([
                'pedido_id' => $pedido->id,
                'talla'     => $tallaSolicitud->talla,
                'cantidad'  => $tallaSolicitud->cantidad,
            ]);
        }

        $solicitud->update(['estado' => 'aceptado', 'pedido_id' => $pedido->id]);

        return redirect()->route('cliente.pedidos.comprobante', $pedido->id)
                         ->with('success', '¡Cotización aceptada! Ahora sube tu comprobante de pago.');
    }

    // ── RECHAZAR LA COTIZACIÓN
    public function rechazar($solicitudId)
    {
        $solicitud = SolicitudDiseno::where('cliente_id', session('usuario_id'))->findOrFail($solicitudId);

        if ($solicitud->estado === 'cotizado') {
            $solicitud->update(['estado' => 'rechazado']);
        }

        return back()->with('success', 'Rechazaste la cotización.');
    }
}
