<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Cliente\CatalogoGeneralController;
use App\Models\InformacionLocal;
use App\Models\Testimonio;
use Illuminate\Http\Request;


class InicioController extends Controller
{
    /**
     * Página de inicio del cliente: banner, categorías, destacados y testimonios.
     */
    public function index()
    {
        return view('cliente.inicio', [
            'info'        => InformacionLocal::actual(),
            'destacados'  => (new CatalogoGeneralController())->destacados(),
            'testimonios' => Testimonio::where('activo', 1)->where('estado', 'aprobado')->orderBy('orden')->orderBy('created_at', 'desc')->take(5)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
