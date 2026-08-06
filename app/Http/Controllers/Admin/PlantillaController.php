<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PedidoPlantillaItem;
use App\Models\Plantilla;
use App\Models\PlantillaTalla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantillaController extends Controller
{
    // ── LISTA DE PLANTILLAS
    public function index()
    {
        $plantillas = Plantilla::with('tallas')->orderBy('created_at', 'desc')->get();
        return view('admin.plantillas.index', compact('plantillas'));
    }

    // ── FORMULARIO CREAR
    public function create()
    {
        return view('admin.plantillas.create');
    }

    // ── GUARDAR PLANTILLA (con sus tallas y precios)
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'tipo_prenda'      => 'required|in:camiseta,short,conjunto,otro',
            'genero'           => 'required|in:hombre,mujer,unisex',
            'descripcion'      => 'nullable|string',
            'tela'             => 'nullable|string|max:100',
            'imagen_preview'   => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'           => 'nullable|boolean',
            'tallas'           => 'required|array|min:1',
            'tallas.*.talla'   => 'required|string|max:10|distinct',
            'tallas.*.precio'  => 'required|numeric|min:0.01|max:250',
        ], [
            'nombre.required'         => 'El nombre es obligatorio.',
            'tipo_prenda.required'    => 'El tipo de prenda es obligatorio.',
            'genero.required'         => 'Selecciona para quién es la prenda.',
            'imagen_preview.required' => 'La imagen es obligatoria.',
            'imagen_preview.image'    => 'El archivo debe ser una imagen.',
            'imagen_preview.max'      => 'La imagen no debe superar 2MB.',
            'tallas.required'         => 'Debes agregar al menos una talla con su precio.',
            'tallas.*.talla.required' => 'Cada fila debe tener una talla.',
            'tallas.*.talla.distinct' => 'No repitas la misma talla dos veces.',
            'tallas.*.precio.required'=> 'Cada talla debe tener su precio.',
            'tallas.*.precio.numeric' => 'El precio debe ser un número.',
            'tallas.*.precio.min'     => 'El precio debe ser mayor a 0.',
            'tallas.*.precio.max'     => 'El precio no debe superar $250.',
        ]);

        $imagenPath = $request->file('imagen_preview')->store('plantillas', 'public');

        $plantilla = Plantilla::create([
            'nombre'         => $request->nombre,
            'tipo_prenda'    => $request->tipo_prenda,
            'genero'         => $request->genero,
            'descripcion'    => $request->descripcion,
            'tela'           => $request->tela,
            'imagen_preview' => $imagenPath,
            'activa'         => $request->has('activa') ? 1 : 0,
            'destacado'      => $request->has('destacado') ? 1 : 0,
        ]);

        foreach ($request->tallas as $fila) {
            PlantillaTalla::create([
                'plantilla_id' => $plantilla->id,
                'talla'        => trim($fila['talla']),
                'precio'       => $fila['precio'],
                'disponible'   => 1,
            ]);
        }

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla creada correctamente.');
    }

    // ── FORMULARIO EDITAR
    public function edit($id)
    {
        $plantilla = Plantilla::with('tallas')->findOrFail($id);
        return view('admin.plantillas.edit', compact('plantilla'));
    }

    // ── ACTUALIZAR PLANTILLA
    public function update(Request $request, $id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $request->validate([
            'nombre'           => 'required|string|max:100',
            'tipo_prenda'      => 'required|in:camiseta,short,conjunto,otro',
            'genero'           => 'required|in:hombre,mujer,unisex',
            'descripcion'      => 'nullable|string',
            'tela'             => 'nullable|string|max:100',
            'imagen_preview'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'           => 'nullable|boolean',
            'tallas'           => 'required|array|min:1',
            'tallas.*.talla'   => 'required|string|max:10|distinct',
            'tallas.*.precio'  => 'required|numeric|min:0.01|max:250',
        ]);

        if ($request->hasFile('imagen_preview')) {
            Storage::disk('public')->delete($plantilla->imagen_preview);
            $plantilla->imagen_preview = $request->file('imagen_preview')->store('plantillas', 'public');
        }

        $plantilla->nombre      = $request->nombre;
        $plantilla->tipo_prenda = $request->tipo_prenda;
        $plantilla->genero      = $request->genero;
        $plantilla->descripcion = $request->descripcion;
        $plantilla->tela        = $request->tela;
        $plantilla->activa      = $request->has('activa') ? 1 : 0;
        $plantilla->destacado   = $request->has('destacado') ? 1 : 0;
        $plantilla->save();

        // Re-sincronizar tallas
        $idsEnviados = [];
        foreach ($request->tallas as $fila) {
            $talla = PlantillaTalla::updateOrCreate(
                ['plantilla_id' => $plantilla->id, 'talla' => trim($fila['talla'])],
                ['precio' => $fila['precio'], 'disponible' => 1]
            );
            $idsEnviados[] = $talla->id;
        }

        PlantillaTalla::where('plantilla_id', $plantilla->id)
            ->whereNotIn('id', $idsEnviados)
            ->get()
            ->each(function ($t) {
                $enUso = PedidoPlantillaItem::where('plantilla_talla_id', $t->id)->exists();
                if ($enUso) {
                    $t->disponible = 0;
                    $t->save();
                } else {
                    $t->delete();
                }
            });

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla actualizada correctamente.');
    }

    // ── ELIMINAR PLANTILLA
    public function destroy($id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $tienePedidos = PedidoPlantillaItem::where('plantilla_id', $plantilla->id)->exists();
        if ($tienePedidos) {
            $plantilla->activa = 0;
            $plantilla->save();
            return redirect()->route('admin.plantillas.index')
                             ->with('success', 'La plantilla tiene pedidos asociados, se desactivó en lugar de eliminarse.');
        }

        Storage::disk('public')->delete($plantilla->imagen_preview);
        $plantilla->delete();

        return redirect()->route('admin.plantillas.index')
                         ->with('success', 'Plantilla eliminada correctamente.');
    }
}
