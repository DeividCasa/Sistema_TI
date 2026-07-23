<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\SolicitudDiseno;
use App\Models\Testimonio;
use App\Support\Notificaciones;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('Admin.panel_admin', function ($view) {
            $view->with('pedidosNuevosCount', Notificaciones::contarPedidosNuevos());
            $view->with('clientesNuevosCount', Notificaciones::contarNuevos('clientes', Cliente::class));
            $view->with('disenios3dNuevosCount', Notificaciones::contarNuevos('disenios3d', SolicitudDiseno::class));
            $view->with('testimoniosPendientesCount', Testimonio::where('estado', 'pendiente')->count());
        });
    }
}
