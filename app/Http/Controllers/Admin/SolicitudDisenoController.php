<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CotizacionDisenoMail;
use App\Models\SolicitudDiseno;
use App\Support\Notificaciones;
use App\Support\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SolicitudDisenoController extends Controller
{
    // ── LISTA DE SOLICITUDES DE DISEÑO 3D
    public function index()
    {
        Notificaciones::marcarVisto('disenios3d');

        $solicitudes = SolicitudDiseno::with(['cliente', 'disenio'])
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        return view('Admin.disenios3d.index', compact('solicitudes'));
    }

    // ── VER DETALLE (fotos, cliente, tela/tallas/descripción) Y COTIZAR
    public function show($id)
    {
        $solicitud = SolicitudDiseno::with(['cliente', 'disenio', 'tallas'])->findOrFail($id);

        return view('Admin.disenios3d.show', compact('solicitud'));
    }

    // ── FIJAR/ACTUALIZAR PRECIO Y MENSAJE PARA EL CLIENTE
    public function cotizar(Request $request, $id)
    {
        $solicitud = SolicitudDiseno::with(['cliente', 'disenio'])->findOrFail($id);

        $request->validate([
            'precio'        => 'required|numeric|min:0|max:250',
            'mensaje_admin' => 'nullable|string|max:1000',
        ]);

        $solicitud->update([
            'precio'        => $request->precio,
            'mensaje_admin' => $request->mensaje_admin,
            'estado'        => $solicitud->estado === 'pendiente' ? 'cotizado' : $solicitud->estado,
        ]);

        if ($request->boolean('notificar_email') && $solicitud->cliente?->email) {
            $imagenPath = $solicitud->disenio?->imagen_generada
                ? \Illuminate\Support\Facades\Storage::disk('public')->path($solicitud->disenio->imagen_generada)
                : null;

            Mail::to($solicitud->cliente->email)->send(new CotizacionDisenoMail(
                $solicitud->cliente->nombre,
                $solicitud->disenio->nombre,
                $solicitud->precio,
                $solicitud->mensaje_admin,
                $imagenPath,
            ));
        }

        $whatsappUrl = null;
        if ($request->boolean('notificar_whatsapp')) {
            $mensaje = "Hola {$solicitud->cliente->nombre}, tu diseño '{$solicitud->disenio->nombre}' ya tiene precio: \${$solicitud->precio}.";
            if ($solicitud->mensaje_admin) {
                $mensaje .= " {$solicitud->mensaje_admin}";
            }
            $whatsappUrl = WhatsappHelper::link($solicitud->cliente?->telefono, $mensaje);
        }

        return redirect()->route('admin.disenios3d.show', $solicitud->id)
                         ->with([
                             'success' => 'Cotización enviada al cliente.',
                             'whatsapp_url' => $whatsappUrl,
                         ]);
    }
}
