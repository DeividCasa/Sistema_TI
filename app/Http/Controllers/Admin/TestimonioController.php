<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonio;
use Illuminate\Support\Facades\Storage;

class TestimonioController extends Controller
{
    const MAXIMO_ACTIVOS = 5;

    // ── COLA DE MODERACIÓN: pendientes primero, luego aprobados/rechazados
    public function index()
    {
        $testimonios = Testimonio::with('cliente')
            ->orderByRaw("FIELD(estado, 'pendiente', 'aprobado', 'rechazado')")
            ->orderBy('created_at', 'desc')
            ->get();

        $activos = $testimonios->where('activo', 1)->count();

        return view('Admin.testimonios.index', compact('testimonios', 'activos'));
    }

    // ── APROBAR (queda visible para el admin, aún no se muestra en inicio)
    public function aprobar($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        $testimonio->estado = 'aprobado';
        $testimonio->save();

        return back()->with('success', 'Testimonio aprobado. Actívalo para que aparezca en el inicio.');
    }

    // ── RECHAZAR
    public function rechazar($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        $testimonio->estado = 'rechazado';
        $testimonio->activo = 0;
        $testimonio->save();

        return back()->with('success', 'Testimonio rechazado.');
    }

    // ── ACTIVAR (mostrar en la página de inicio) — máximo 5 a la vez
    public function activar($id)
    {
        $testimonio = Testimonio::findOrFail($id);

        if ($testimonio->estado !== 'aprobado') {
            return back()->withErrors(['testimonio' => 'Primero debes aprobar el testimonio.']);
        }

        if (Testimonio::where('activo', 1)->count() >= self::MAXIMO_ACTIVOS) {
            return back()->withErrors(['testimonio' => 'Ya tienes ' . self::MAXIMO_ACTIVOS . ' testimonios mostrándose. Desactiva uno primero.']);
        }

        $testimonio->activo = 1;
        $testimonio->save();

        return back()->with('success', 'Testimonio activado en la página de inicio.');
    }

    // ── DESACTIVAR (dejar de mostrar en inicio, sin borrarlo)
    public function desactivar($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        $testimonio->activo = 0;
        $testimonio->save();

        return back()->with('success', 'Testimonio retirado del inicio.');
    }

    // ── ELIMINAR
    public function destroy($id)
    {
        $testimonio = Testimonio::findOrFail($id);

        if ($testimonio->imagen) {
            Storage::disk('public')->delete($testimonio->imagen);
        }
        $testimonio->delete();

        return redirect()->route('admin.testimonios.index')
                         ->with('success', 'Testimonio eliminado correctamente.');
    }
}
