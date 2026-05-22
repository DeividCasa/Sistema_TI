<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LoginController;

Route::get('/', [InicioController::class, 'index'])
->name('inicio');

Route::get('/login', function () {

    return view('login.login_correo');

})->name('login.paso1');

Route::post('/login/contrasena', [LoginController::class, 'guardarCorreo'])
->name('guardar.correo');

Route::get('/login/contrasena', function () {

    return view('login.login_contra');

})->name('login.paso2');

Route::post('/validar-login', [LoginController::class, 'validarLogin'])
->name('validar.login');

Route::get('/Pagina_central', function () {

    return view('Pagina_central.inicio_principal');

})->name('inicio_principal');

Route::get('/admin_ini', function () {

    return view('admin.admin_ini');

})->name('admin_ini');