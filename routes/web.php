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
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Plantilla;

// ── PÁGINA DE INICIO
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// ── REGISTRO
Route::get('/registro', [RegistroController::class, 'show'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

// ── LOGIN EN 2 PASOS
Route::get('/login', [LoginController::class, 'showCorreo'])->name('login.paso1');
Route::post('/login/verificar-correo', [LoginController::class, 'verificarCorreo'])->name('login.verificar-correo');
Route::get('/login/contrasena', [LoginController::class, 'showContrasena'])->name('login.paso2');
Route::post('/login/verificar-contrasena', [LoginController::class, 'verificarContrasena'])->name('login.verificar-contrasena');

// ── LOGOUT
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login.paso1');
})->name('logout');

// ── ZONA PÚBLICA CLIENTE (catálogo)
Route::get('/Pagina_central', function () {
    $plantillas = Plantilla::where('activa', 1)->get();
    return view('Pagina_central.inicio_principal', compact('plantillas'));
})->name('cliente.inicio');

// ── ZONA CLIENTE (requiere login cliente)
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
});

// ── ZONA ADMIN (requiere login admin)
Route::middleware('sesion:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        $total_pedidos     = Pedido::count();
        $en_produccion     = Pedido::where('estado', 'en_produccion')->count();
        $listos            = Pedido::where('estado', 'listo')->count();
        $entregados        = Pedido::where('estado', 'entregado')->count();
        $recibidos         = Pedido::where('estado', 'recibido')->count();
        $cancelados        = Pedido::where('estado', 'cancelado')->count();
        $total_clientes    = Cliente::count();
        $total_plantillas  = Plantilla::where('activa', 1)->count();
        $pedidos_recientes = Pedido::with('cliente')->orderBy('created_at', 'desc')->take(5)->get();
        $pedidos_por_mes   = Pedido::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
                                   ->whereYear('created_at', date('Y'))
                                   ->groupBy('mes')->orderBy('mes')->get();
        return view('Admin.admin_ini', compact(
            'total_pedidos','en_produccion','listos','entregados',
            'recibidos','cancelados','total_clientes','total_plantillas',
            'pedidos_recientes','pedidos_por_mes'
        ));
    })->name('inicio');

    Route::resource('plantillas', PlantillaController::class);
    Route::resource('pedidos', PedidoController::class)->only(['index', 'show', 'update']);
    Route::resource('clientes', ClienteController::class)->only(['index', 'show']);

    // Verificar/rechazar comprobantes
    Route::post('/comprobantes/{id}/verificar', [ComprobanteController::class, 'verificar'])->name('comprobantes.verificar');
    Route::post('/comprobantes/{id}/rechazar', [ComprobanteController::class, 'rechazar'])->name('comprobantes.rechazar');
});

// Redirigir /admin_ini al nuevo dashboard
Route::get('/admin_ini', function () {
    return redirect()->route('admin.inicio');
});