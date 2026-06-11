<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantillaController extends Controller
{
    // ── LISTA DE PLANTILLAS
    public function index()
    {
        $plantillas = Plantilla::orderBy('created_at', 'desc')->get();
        return view('admin.plantillas.index', compact('plantillas'));
    }

    // ── FORMULARIO CREAR
    public function create()
    {
        return view('admin.plantillas.create');
    }

    // ── GUARDAR PLANTILLA
    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:100',
            'tipo_prenda'    => 'required|in:camiseta,short,conjunto,otro',
            'imagen_preview' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'         => 'nullable|boolean',
        ], [
            'nombre.required'         => 'El nombre es obligatorio.',
            'tipo_prenda.required'    => 'El tipo de prenda es obligatorio.',
            'imagen_preview.required' => 'La imagen es obligatoria.',
            'imagen_preview.image'    => 'El archivo debe ser una imagen.',
            'imagen_preview.max'      => 'La imagen no debe superar 2MB.',
        ]);

        $imagenPath = $request->file('imagen_preview')->store('plantillas', 'public');

        Plantilla::create([
            'nombre'         => $request->nombre,
            'tipo_prenda'    => $request->tipo_prenda,
            'imagen_preview' => $imagenPath,
            'activa'         => $request->has('activa') ? 1 : 0,
        ]);

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla creada correctamente.');
    }

    // ── FORMULARIO EDITAR
    public function edit($id)
    {
        $plantilla = Plantilla::findOrFail($id);
        return view('admin.plantillas.edit', compact('plantilla'));
    }

    // ── ACTUALIZAR PLANTILLA
    public function update(Request $request, $id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $request->validate([
            'nombre'         => 'required|string|max:100',
            'tipo_prenda'    => 'required|in:camiseta,short,conjunto,otro',
            'imagen_preview' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'         => 'nullable|boolean',
        ]);

        if ($request->hasFile('imagen_preview')) {
            // Eliminar imagen anterior
            Storage::disk('public')->delete($plantilla->imagen_preview);
            $imagenPath = $request->file('imagen_preview')->store('plantillas', 'public');
            $plantilla->imagen_preview = $imagenPath;
        }

        $plantilla->nombre      = $request->nombre;
        $plantilla->tipo_prenda = $request->tipo_prenda;
        $plantilla->activa      = $request->has('activa') ? 1 : 0;
        $plantilla->save();

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla actualizada correctamente.');
    }

    // ── ELIMINAR PLANTILLA
    public function destroy($id)
    {
        $plantilla = Plantilla::findOrFail($id);
        Storage::disk('public')->delete($plantilla->imagen_preview);
        $plantilla->delete();

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla eliminada correctamente.');
    }
}