<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chompa;
use App\Models\ChompaTalla;
use App\Models\PedidoChompaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChompaController extends Controller
{
    // ── LISTA DE CHOMPAS
    public function index()
    {
        $chompas = Chompa::with('tallas')->orderBy('created_at', 'desc')->get();
        return view('Admin.chompas.index', compact('chompas'));
    }

    // ── FORMULARIO CREAR
    public function create()
    {
        return view('Admin.chompas.create');
    }

    // ── GUARDAR CHOMPA (con sus tallas y precios)
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'tipo_tela'        => 'required|string|max:100',
            'genero'           => 'required|in:hombre,mujer,unisex',
            'descripcion'      => 'nullable|string',
            'imagen'           => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tallas'           => 'required|array|min:1',
            'tallas.*.talla'   => 'required|string|max:10|distinct',
            'tallas.*.precio'  => 'required|numeric|min:0.01',
        ], [
            'nombre.required'          => 'El nombre de la chompa es obligatorio.',
            'tipo_tela.required'       => 'El tipo de tela es obligatorio.',
            'genero.required'          => 'Selecciona para quién es la chompa.',
            'imagen.required'          => 'La foto de la chompa es obligatoria.',
            'imagen.image'             => 'El archivo debe ser una imagen.',
            'imagen.max'               => 'La imagen no debe superar 2MB.',
            'tallas.required'          => 'Debes agregar al menos una talla con su precio.',
            'tallas.*.talla.required'  => 'Cada fila debe tener una talla.',
            'tallas.*.talla.distinct'  => 'No repitas la misma talla dos veces.',
            'tallas.*.precio.required' => 'Cada talla debe tener su precio.',
            'tallas.*.precio.numeric'  => 'El precio debe ser un número.',
            'tallas.*.precio.min'      => 'El precio debe ser mayor a 0.',
        ]);

        $imagenPath = $request->file('imagen')->store('chompas', 'public');

        $chompa = Chompa::create([
            'nombre'      => $request->nombre,
            'tipo_tela'   => $request->tipo_tela,
            'genero'      => $request->genero,
            'descripcion' => $request->descripcion,
            'imagen'      => $imagenPath,
            'activo'      => $request->has('activo') ? 1 : 0,
        ]);

        foreach ($request->tallas as $fila) {
            ChompaTalla::create([
                'chompa_id'  => $chompa->id,
                'talla'      => trim($fila['talla']),
                'precio'     => $fila['precio'],
                'disponible' => 1,
            ]);
        }

        return redirect()->route('admin.chompas.index')
                         ->with('success', 'Chompa registrada correctamente con sus tallas y precios.');
    }

    // ── FORMULARIO EDITAR
    public function edit($id)
    {
        $chompa = Chompa::with('tallas')->findOrFail($id);
        return view('Admin.chompas.edit', compact('chompa'));
    }

    // ── ACTUALIZAR CHOMPA
    public function update(Request $request, $id)
    {
        $chompa = Chompa::findOrFail($id);

        $request->validate([
            'nombre'           => 'required|string|max:150',
            'tipo_tela'        => 'required|string|max:100',
            'genero'           => 'required|in:hombre,mujer,unisex',
            'descripcion'      => 'nullable|string',
            'imagen'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tallas'           => 'required|array|min:1',
            'tallas.*.talla'   => 'required|string|max:10|distinct',
            'tallas.*.precio'  => 'required|numeric|min:0.01',
        ]);

        if ($request->hasFile('imagen')) {
            if ($chompa->imagen) {
                Storage::disk('public')->delete($chompa->imagen);
            }
            $chompa->imagen = $request->file('imagen')->store('chompas', 'public');
        }

        $chompa->nombre      = $request->nombre;
        $chompa->tipo_tela   = $request->tipo_tela;
        $chompa->genero      = $request->genero;
        $chompa->descripcion = $request->descripcion;
        $chompa->activo      = $request->has('activo') ? 1 : 0;
        $chompa->save();

        // Re-sincronizar tallas
        $idsEnviados = [];
        foreach ($request->tallas as $fila) {
            $talla = ChompaTalla::updateOrCreate(
                ['chompa_id' => $chompa->id, 'talla' => trim($fila['talla'])],
                ['precio' => $fila['precio'], 'disponible' => 1]
            );
            $idsEnviados[] = $talla->id;
        }

        ChompaTalla::where('chompa_id', $chompa->id)
            ->whereNotIn('id', $idsEnviados)
            ->get()
            ->each(function ($t) {
                $enUso = PedidoChompaItem::where('chompa_talla_id', $t->id)->exists();
                if ($enUso) {
                    $t->disponible = 0;
                    $t->save();
                } else {
                    $t->delete();
                }
            });

        return redirect()->route('admin.chompas.index')
                         ->with('success', 'Chompa actualizada correctamente.');
    }

    // ── ELIMINAR (desactivar si tiene pedidos)
    public function destroy($id)
    {
        $chompa = Chompa::findOrFail($id);

        $tienePedidos = PedidoChompaItem::where('chompa_id', $chompa->id)->exists();
        if ($tienePedidos) {
            $chompa->activo = 0;
            $chompa->save();
            return redirect()->route('admin.chompas.index')
                             ->with('success', 'La chompa tiene pedidos asociados, se desactivó en lugar de eliminarse.');
        }

        if ($chompa->imagen) {
            Storage::disk('public')->delete($chompa->imagen);
        }
        $chompa->delete();

        return redirect()->route('admin.chompas.index')
                         ->with('success', 'Chompa eliminada correctamente.');
    }
}
