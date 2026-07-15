<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\Admin\PlantillaController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ComprobanteController;
use App\Http\Controllers\Cliente\PedidoController as ClientePedidoController;
use App\Http\Controllers\Cliente\DisenioController;
use App\Http\Controllers\Cliente\LogoController;
use App\Http\Controllers\Cliente\SolicitudDisenoController;
use App\Http\Controllers\Admin\SolicitudDisenoController as AdminSolicitudDisenoController;
use App\Http\Controllers\Admin\UniformeController;
use App\Http\Controllers\Admin\PedidoUniformeController;
use App\Http\Controllers\Cliente\UniformeClienteController;
use App\Http\Controllers\Cliente\CarritoUniformeController;

use App\Http\Controllers\Admin\ChompaController;
use App\Http\Controllers\Admin\PedidoChompaController;
use App\Http\Controllers\Cliente\ChompaClienteController;
use App\Http\Controllers\Cliente\CarritoChompaController;
use App\Http\Controllers\Cliente\CarritoMaestroController;
use App\Http\Controllers\Cliente\CarritoPlantillaController;
use App\Http\Controllers\Cliente\CatalogoGeneralController;
use App\Http\Controllers\Admin\PedidoTiendaController;
use App\Http\Controllers\Admin\PedidoPlantillaController;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Plantilla;


Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('/registro', [RegistroController::class, 'show'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');
Route::get('/login', [LoginController::class, 'showCorreo'])->name('login.paso1');
Route::post('/login/verificar-correo', [LoginController::class, 'verificarCorreo'])->name('login.verificar-correo');
Route::get('/login/contrasena', [LoginController::class, 'showContrasena'])->name('login.paso2');
Route::post('/login/verificar-contrasena', [LoginController::class, 'verificarContrasena'])->name('login.verificar-contrasena');

Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login.paso1');
})->name('logout');

// Toda la ropa: catálogo único (camisetas, shorts, conjuntos, uniformes y chompas
// mezclados). Es la página de inicio del cliente y reemplaza las secciones que
// antes estaban separadas (cada una con su propio filtro).
Route::get('/toda-la-ropa', [CatalogoGeneralController::class, 'index'])
    ->name('cliente.catalogo.index');

Route::get('/Pagina_central', function () {
    return redirect()->route('cliente.catalogo.index');
})->name('cliente.inicio');

// Rutas públicas de uniformes (fuera del middleware de sesión)
Route::get('/uniformes-escolares', function () {
    return redirect()->route('cliente.catalogo.index', ['categoria' => 'uniforme']);
})->name('cliente.uniformes.index');

Route::get('/uniformes-escolares/{id}', [UniformeClienteController::class, 'show'])
    ->name('cliente.uniformes.show');

Route::middleware('sesion:cliente')->group(function () {

    Route::get('/producto/{id}', function ($id) {
        $plantilla = Plantilla::findOrFail($id);
        return view('cliente.producto', compact('plantilla'));
    })->name('producto.ver');

    Route::post('/pedidos', [ClientePedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}/comprobante', [ClientePedidoController::class, 'comprobante'])->name('cliente.pedidos.comprobante');
    Route::post('/pedidos/{id}/comprobante', [ClientePedidoController::class, 'guardarComprobante'])->name('cliente.pedidos.comprobante.store');
    Route::get('/mis-pedidos', [ClientePedidoController::class, 'index'])->name('cliente.pedidos.index');

    // ── CREA TU DISEÑO CON IA / PERSONALIZAR
    Route::get('/personalizar/{plantilla?}', [DisenioController::class, 'create'])->name('disenios.create');
    Route::post('/disenios', [DisenioController::class, 'store'])->name('disenios.store');
    Route::post('/disenios/generar-ia', [DisenioController::class, 'generarIA'])->name('disenios.generar-ia');
    Route::delete('/disenios/{id}', [DisenioController::class, 'destroy'])->name('disenios.destroy');

    // ── GALERÍA DE LOGOS DEL CLIENTE (privada por cuenta)
    Route::get('/logos', [LogoController::class, 'index'])->name('logos.index');
    Route::post('/logos', [LogoController::class, 'store'])->name('logos.store');
    Route::delete('/logos/{id}', [LogoController::class, 'destroy'])->name('logos.destroy');

    // ── MIS DISEÑOS Y SOLICITUD DE COTIZACIÓN
    Route::get('/mis-disenios', [SolicitudDisenoController::class, 'index'])->name('cliente.disenios.index');
    Route::get('/disenios/{disenio}/cotizar', [SolicitudDisenoController::class, 'create'])->name('solicitudes.create');
    Route::post('/disenios/{disenio}/cotizar', [SolicitudDisenoController::class, 'store'])->name('solicitudes.store');
    Route::post('/solicitudes/{solicitud}/aceptar', [SolicitudDisenoController::class, 'aceptar'])->name('solicitudes.aceptar');
    Route::post('/solicitudes/{solicitud}/rechazar', [SolicitudDisenoController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Rutas de carrito y pedidos de uniformes (requieren sesión)
    Route::get('/carrito', [CarritoUniformeController::class, 'index'])
        ->name('cliente.carrito.index');

    Route::post('/carrito/agregar', [CarritoUniformeController::class, 'agregar'])
        ->name('cliente.carrito.agregar');

    Route::post('/carrito/actualizar/{key}', [CarritoUniformeController::class, 'actualizar'])
        ->name('cliente.carrito.actualizar');

    Route::delete('/carrito/quitar/{key}', [CarritoUniformeController::class, 'quitar'])
        ->name('cliente.carrito.quitar');

    Route::post('/carrito/vaciar', [CarritoUniformeController::class, 'vaciar'])
        ->name('cliente.carrito.vaciar');

    Route::post('/carrito/confirmar', [CarritoUniformeController::class, 'confirmar'])
        ->name('cliente.carrito.confirmar');

    // Carrito de ropa (camisetas, shorts, conjuntos, otros)
    Route::post('/carrito/plantillas/agregar', [CarritoPlantillaController::class, 'agregar'])
        ->name('cliente.plantillas.agregar');

    Route::post('/carrito/plantillas/actualizar/{key}', [CarritoPlantillaController::class, 'actualizar'])
        ->name('cliente.plantillas.actualizar');

    Route::delete('/carrito/plantillas/quitar/{key}', [CarritoPlantillaController::class, 'quitar'])
        ->name('cliente.plantillas.quitar');

    Route::post('/carrito/plantillas/vaciar', [CarritoPlantillaController::class, 'vaciar'])
        ->name('cliente.plantillas.vaciar');

    Route::get('/plantillas/pedido/{id}/pago', [CarritoPlantillaController::class, 'pago'])
        ->name('cliente.plantillas.pago');

    Route::post('/plantillas/pedido/{id}/comprobante', [CarritoPlantillaController::class, 'guardarComprobante'])
        ->name('cliente.plantillas.comprobante');

    Route::get('/mis-pedidos-ropa', [CarritoPlantillaController::class, 'misPedidos'])
        ->name('cliente.plantillas.mis-pedidos');

    Route::get('/uniformes/pedido/{id}/pago', [CarritoUniformeController::class, 'pago'])
        ->name('cliente.uniformes.pago');

    Route::post('/uniformes/pedido/{id}/comprobante', [CarritoUniformeController::class, 'guardarComprobante'])
        ->name('cliente.uniformes.comprobante');

    Route::get('/mis-pedidos-uniformes', [CarritoUniformeController::class, 'misPedidos'])
        ->name('cliente.uniformes.mis-pedidos');

    // Rutas de chompas (requieren sesión)
    Route::get('/chompas', function () {
        return redirect()->route('cliente.catalogo.index', ['categoria' => 'chompa']);
    })->name('cliente.chompas.index');

    Route::get('/chompas/{id}', [ChompaClienteController::class, 'show'])
        ->name('cliente.chompas.show');

    Route::get('/carrito-chompas', [CarritoChompaController::class, 'index'])
        ->name('cliente.chompas.carrito');

    Route::post('/carrito-chompas/agregar', [CarritoChompaController::class, 'agregar'])
        ->name('cliente.chompas.agregar');

    Route::post('/carrito-chompas/actualizar/{key}', [CarritoChompaController::class, 'actualizar'])
        ->name('cliente.chompas.actualizar');

    Route::delete('/carrito-chompas/quitar/{key}', [CarritoChompaController::class, 'quitar'])
        ->name('cliente.chompas.quitar');

    Route::post('/carrito-chompas/vaciar', [CarritoChompaController::class, 'vaciar'])
        ->name('cliente.chompas.vaciar');

    Route::post('/carrito-chompas/confirmar', [CarritoChompaController::class, 'confirmar'])
        ->name('cliente.chompas.confirmar');

    Route::get('/chompas/pedido/{id}/pago', [CarritoChompaController::class, 'pago'])
        ->name('cliente.chompas.pago');

    Route::post('/chompas/pedido/{id}/comprobante', [CarritoChompaController::class, 'guardarComprobante'])
        ->name('cliente.chompas.comprobante');

    Route::get('/mis-pedidos-chompas', [CarritoChompaController::class, 'misPedidos'])
        ->name('cliente.chompas.mis-pedidos');

    // Rutas del pedido maestro (uniforme + chompa combinados en un solo pedido)
    Route::get('/pedido-maestro/{id}/pago', [CarritoMaestroController::class, 'pago'])
        ->name('cliente.pedido-maestro.pago');

    Route::post('/pedido-maestro/{id}/comprobante', [CarritoMaestroController::class, 'guardarComprobante'])
        ->name('cliente.pedido-maestro.comprobante');

    Route::get('/mis-pedidos-tienda', [CarritoMaestroController::class, 'misPedidos'])
        ->name('cliente.mis-pedidos');
});

Route::middleware('sesion:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {

        $total_pedidos = Pedido::count();
        $en_produccion = Pedido::where('estado', 'en_produccion')->count();
        $listos = Pedido::where('estado', 'listo')->count();
        $entregados = Pedido::where('estado', 'entregado')->count();
        $recibidos = Pedido::where('estado', 'recibido')->count();
        $cancelados = Pedido::where('estado', 'cancelado')->count();
        $total_clientes = Cliente::count();
        $total_plantillas = Plantilla::where('activa', 1)->count();

        $pedidos_recientes = Pedido::with('cliente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pedidos_por_mes = Pedido::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('Admin.admin_ini', compact(
            'total_pedidos',
            'en_produccion',
            'listos',
            'entregados',
            'recibidos',
            'cancelados',
            'total_clientes',
            'total_plantillas',
            'pedidos_recientes',
            'pedidos_por_mes'
        ));

    })->name('inicio');

    Route::resource('plantillas', PlantillaController::class);

    Route::resource('pedidos', PedidoController::class)
        ->only(['show', 'update']);

    Route::post('/pedidos/{id}/pago-completo', [PedidoController::class, 'marcarPagoCompleto'])
        ->name('pedidos.pago-completo');

    Route::resource('clientes', ClienteController::class)
        ->only(['index', 'show']);

    // ── DISEÑOS 3D: solicitudes de cotización de clientes
    Route::get('/disenios-3d', [AdminSolicitudDisenoController::class, 'index'])->name('disenios3d.index');
    Route::get('/disenios-3d/{id}', [AdminSolicitudDisenoController::class, 'show'])->name('disenios3d.show');
    Route::post('/disenios-3d/{id}/cotizar', [AdminSolicitudDisenoController::class, 'cotizar'])->name('disenios3d.cotizar');

    Route::post('/comprobantes/{id}/verificar', [ComprobanteController::class, 'verificar'])
        ->name('comprobantes.verificar');

    Route::post('/comprobantes/{id}/rechazar', [ComprobanteController::class, 'rechazar'])
        ->name('comprobantes.rechazar');

    Route::resource('uniformes', UniformeController::class)
        ->except(['show']);

    Route::resource('pedidos-uniformes', PedidoUniformeController::class)
        ->only(['show', 'update'])
        ->parameters([
            'pedidos-uniformes' => 'id'
        ]);

    Route::post('/pedidos-uniformes/{id}/pago-completo', [PedidoUniformeController::class, 'marcarPagoCompleto'])
        ->name('pedidos-uniformes.pago-completo');

    Route::post('/comprobantes-uniformes/{id}/verificar',
        [PedidoUniformeController::class, 'verificarComprobante'])
        ->name('comprobantes-uniformes.verificar');

    Route::post('/comprobantes-uniformes/{id}/rechazar',
        [PedidoUniformeController::class, 'rechazarComprobante'])
        ->name('comprobantes-uniformes.rechazar');


    Route::resource('chompas', ChompaController::class)
        ->except(['show']);

    Route::resource('pedidos-chompas', PedidoChompaController::class)
        ->only(['show', 'update'])
        ->parameters([
            'pedidos-chompas' => 'id'
        ]);

    Route::post('/pedidos-chompas/{id}/pago-completo', [PedidoChompaController::class, 'marcarPagoCompleto'])
        ->name('pedidos-chompas.pago-completo');

    Route::post('/comprobantes-chompas/{id}/verificar',
        [PedidoChompaController::class, 'verificarComprobante'])
        ->name('comprobantes-chompas.verificar');

    Route::post('/comprobantes-chompas/{id}/rechazar',
        [PedidoChompaController::class, 'rechazarComprobante'])
        ->name('comprobantes-chompas.rechazar');

    Route::resource('pedidos-plantillas', PedidoPlantillaController::class)
        ->only(['show', 'update'])
        ->parameters([
            'pedidos-plantillas' => 'id'
        ]);

    Route::post('/pedidos-plantillas/{id}/pago-completo', [PedidoPlantillaController::class, 'marcarPagoCompleto'])
        ->name('pedidos-plantillas.pago-completo');

    Route::post('/comprobantes-plantillas/{id}/verificar',
        [PedidoPlantillaController::class, 'verificarComprobante'])
        ->name('comprobantes-plantillas.verificar');

    Route::post('/comprobantes-plantillas/{id}/rechazar',
        [PedidoPlantillaController::class, 'rechazarComprobante'])
        ->name('comprobantes-plantillas.rechazar');

    // ── PEDIDOS DE TIENDA: lista unificada de uniformes + chompas (sueltos o combinados)
    Route::get('/pedidos-tienda', [PedidoTiendaController::class, 'index'])->name('pedidos-tienda.index');
    Route::get('/pedidos-tienda/{id}', [PedidoTiendaController::class, 'show'])->name('pedidos-tienda.show');
    Route::post('/pedidos-tienda/{id}/pago-completo', [PedidoTiendaController::class, 'marcarPagoCompleto'])
        ->name('pedidos-tienda.pago-completo');
    Route::post('/comprobantes-maestro/{id}/verificar', [PedidoTiendaController::class, 'verificarComprobante'])
        ->name('comprobantes-maestro.verificar');
    Route::post('/comprobantes-maestro/{id}/rechazar', [PedidoTiendaController::class, 'rechazarComprobante'])
        ->name('comprobantes-maestro.rechazar');
});

Route::get('/admin_ini', function () {
    return redirect()->route('admin.inicio');
});