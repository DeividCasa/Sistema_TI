<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudDiseno;
use App\Support\Notificaciones;
use Illuminate\Http\Request;

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
        $solicitud = SolicitudDiseno::findOrFail($id);

        $request->validate([
            'precio'        => 'required|numeric|min:0',
            'mensaje_admin' => 'nullable|string|max:1000',
        ]);

        $solicitud->update([
            'precio'        => $request->precio,
            'mensaje_admin' => $request->mensaje_admin,
            'estado'        => $solicitud->estado === 'pendiente' ? 'cotizado' : $solicitud->estado,
        ]);

        return redirect()->route('admin.disenios3d.show', $solicitud->id)
                         ->with('success', 'Cotización enviada al cliente.');
    }
}
