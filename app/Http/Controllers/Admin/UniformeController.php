<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Uniforme;
use App\Models\UniformeTalla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniformeController extends Controller
{
    // ── LISTA DE UNIFORMES
    public function index()
    {
        $uniformes = Uniforme::with('tallas')->orderBy('created_at', 'desc')->get();
        return view('Admin.uniformes.index', compact('uniformes'));
    }

    // ── FORMULARIO CREAR
    public function create()
    {
        return view('Admin.uniformes.create');
    }

    // ── GUARDAR UNIFORME (con sus tallas y precios)
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
            'nombre.required'          => 'El nombre del uniforme es obligatorio.',
            'tipo_tela.required'       => 'El tipo de tela es obligatorio.',
            'genero.required'          => 'Selecciona para quién es el uniforme.',
            'imagen.required'          => 'La foto del uniforme es obligatoria.',
            'imagen.image'             => 'El archivo debe ser una imagen.',
            'imagen.max'               => 'La imagen no debe superar 2MB.',
            'tallas.required'          => 'Debes agregar al menos una talla con su precio.',
            'tallas.*.talla.required'  => 'Cada fila debe tener una talla.',
            'tallas.*.talla.distinct'  => 'No repitas la misma talla dos veces.',
            'tallas.*.precio.required' => 'Cada talla debe tener su precio.',
            'tallas.*.precio.numeric'  => 'El precio debe ser un número.',
            'tallas.*.precio.min'      => 'El precio debe ser mayor a 0.',
        ]);

        $imagenPath = $request->file('imagen')->store('uniformes', 'public');

        $uniforme = Uniforme::create([
            'nombre'      => $request->nombre,
            'tipo_tela'   => $request->tipo_tela,
            'genero'      => $request->genero,
            'descripcion' => $request->descripcion,
            'imagen'      => $imagenPath,
            'activo'      => $request->has('activo') ? 1 : 0,
            'destacado'   => $request->has('destacado') ? 1 : 0,
        ]);

        // Guardar cada talla con su precio
        foreach ($request->tallas as $fila) {
            UniformeTalla::create([
                'uniforme_id' => $uniforme->id,
                'talla'       => trim($fila['talla']),
                'precio'      => $fila['precio'],
                'disponible'  => 1,
            ]);
        }

        return redirect()->route('admin.uniformes.index')
                         ->with('success', 'Uniforme registrado correctamente con sus tallas y precios.');
    }

    // ── FORMULARIO EDITAR
    public function edit($id)
    {
        $uniforme = Uniforme::with('tallas')->findOrFail($id);
        return view('Admin.uniformes.edit', compact('uniforme'));
    }

    // ── ACTUALIZAR UNIFORME
    public function update(Request $request, $id)
    {
        $uniforme = Uniforme::findOrFail($id);

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

        // Si sube una nueva imagen, borrar la anterior
        if ($request->hasFile('imagen')) {
            if ($uniforme->imagen) {
                Storage::disk('public')->delete($uniforme->imagen);
            }
            $uniforme->imagen = $request->file('imagen')->store('uniformes', 'public');
        }

        $uniforme->nombre      = $request->nombre;
        $uniforme->tipo_tela   = $request->tipo_tela;
        $uniforme->genero      = $request->genero;
        $uniforme->descripcion = $request->descripcion;
        $uniforme->activo      = $request->has('activo') ? 1 : 0;
        $uniforme->destacado   = $request->has('destacado') ? 1 : 0;
        $uniforme->save();

        // Re-sincronizar tallas: se borran las que no vienen y se actualizan/crean las demás
        $idsEnviados = [];
        foreach ($request->tallas as $fila) {
            $talla = UniformeTalla::updateOrCreate(
                ['uniforme_id' => $uniforme->id, 'talla' => trim($fila['talla'])],
                ['precio' => $fila['precio'], 'disponible' => 1]
            );
            $idsEnviados[] = $talla->id;
        }
        // Eliminar tallas quitadas del formulario (si están en pedidos solo se desactivan)
        UniformeTalla::where('uniforme_id', $uniforme->id)
            ->whereNotIn('id', $idsEnviados)
            ->get()
            ->each(function ($t) {
                $enUso = \App\Models\PedidoUniformeItem::where('uniforme_talla_id', $t->id)->exists();
                if ($enUso) {
                    $t->disponible = 0; // no borrar, solo desactivar
                    $t->save();
                } else {
                    $t->delete();
                }
            });

        return redirect()->route('admin.uniformes.index')
                         ->with('success', 'Uniforme actualizado correctamente.');
    }

    // ── ELIMINAR (desactivar si tiene pedidos)
    public function destroy($id)
    {
        $uniforme = Uniforme::findOrFail($id);

        $tienePedidos = \App\Models\PedidoUniformeItem::where('uniforme_id', $uniforme->id)->exists();
        if ($tienePedidos) {
            $uniforme->activo = 0;
            $uniforme->save();
            return redirect()->route('admin.uniformes.index')
                             ->with('success', 'El uniforme tiene pedidos asociados, se desactivó en lugar de eliminarse.');
        }

        if ($uniforme->imagen) {
            Storage::disk('public')->delete($uniforme->imagen);
        }
        $uniforme->delete();

        return redirect()->route('admin.uniformes.index')
                         ->with('success', 'Uniforme eliminado correctamente.');
    }
}
