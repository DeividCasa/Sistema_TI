<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\PlantillaController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Plantilla;
use App\Http\Controllers\RegistroController;

// ── PÁGINA DE INICIO
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// ── LOGIN PASO 1 — Correo
Route::get('/login', [LoginController::class, 'showCorreo'])->name('login.paso1');
Route::post('/login/verificar-correo', [LoginController::class, 'verificarCorreo'])->name('login.verificar-correo');

// ── LOGIN PASO 2 — Contraseña
Route::get('/login/contrasena', [LoginController::class, 'showContrasena'])->name('login.paso2');
Route::post('/login/verificar-contrasena', [LoginController::class, 'verificarContrasena'])->name('login.verificar-contrasena');

// ── ZONA CLIENTE
Route::get('/Pagina_central', function () {
    $plantillas = \App\Models\Plantilla::where('activa', 1)->get();
    return view('Pagina_central.inicio_principal', compact('plantillas'));
})->name('cliente.inicio');

// ── ZONA ADMIN
Route::get('/admin_ini', function () {
    return view('admin.admin_ini');
})->name('admin.inicio');


// ── CRUD PLANTILLAS ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('plantillas', PlantillaController::class);
});



// ── CRUD PLANTILLAS + PEDIDOS ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('plantillas', PlantillaController::class);
    Route::resource('pedidos', PedidoController::class)->only(['index', 'show', 'update']);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('plantillas', PlantillaController::class);
    Route::resource('pedidos', PedidoController::class)->only(['index', 'show', 'update']);
    Route::resource('clientes', ClienteController::class)->only(['index', 'show']);
});

Route::get('/admin_ini', function () {
    $total_pedidos    = Pedido::count();
    $en_produccion    = Pedido::where('estado', 'en_produccion')->count();
    $listos           = Pedido::where('estado', 'listo')->count();
    $entregados       = Pedido::where('estado', 'entregado')->count();
    $recibidos        = Pedido::where('estado', 'recibido')->count();
    $cancelados       = Pedido::where('estado', 'cancelado')->count();
    $total_clientes   = Cliente::count();
    $total_plantillas = Plantilla::where('activa', 1)->count();
    $pedidos_recientes = Pedido::with('cliente')
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();

    // Pedidos por mes (últimos 6 meses)
    $pedidos_por_mes = Pedido::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
                             ->whereYear('created_at', date('Y'))
                             ->groupBy('mes')
                             ->orderBy('mes')
                             ->get();

    // Pedidos por tipo de prenda
    $por_tipo = \App\Models\Plantilla::selectRaw('tipo_prenda, COUNT(*) as total')
                                     ->groupBy('tipo_prenda')
                                     ->get();

    return view('admin.admin_ini', compact(
        'total_pedidos', 'en_produccion', 'listos', 'entregados',
        'recibidos', 'cancelados', 'total_clientes', 'total_plantillas',
        'pedidos_recientes', 'pedidos_por_mes', 'por_tipo'
    ));
})->name('admin.inicio');


Route::get('/registro', [RegistroController::class, 'show'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');