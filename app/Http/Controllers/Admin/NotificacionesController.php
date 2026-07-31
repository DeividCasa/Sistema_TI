<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\SolicitudDiseno;
use App\Models\Testimonio;
use App\Support\Notificaciones;
use Illuminate\Http\JsonResponse;

class NotificacionesController extends Controller
{
    public function contador(): JsonResponse
    {
        return response()->json([
            'pedidos'   => Notificaciones::contarPedidosNuevos(),
            'clientes'  => Notificaciones::contarNuevos('clientes', Cliente::class),
            'disenios3d' => Notificaciones::contarNuevos('disenios3d', SolicitudDiseno::class),
            'testimonios' => Testimonio::where('estado', 'pendiente')->count(),
        ]);
    }
}
