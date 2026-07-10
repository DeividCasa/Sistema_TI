<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoController extends Controller
{
    // ── LISTAR LOGOS GUARDADOS DEL CLIENTE (galería privada por cuenta)
    public function index()
    {
        $clienteId = session('usuario_id');

        $logos = Logo::where('cliente_id', $clienteId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($logo) => [
                'id'     => $logo->id,
                'nombre' => $logo->nombre,
                'url'    => asset('storage/' . $logo->ruta),
            ]);

        return response()->json(['success' => true, 'logos' => $logos]);
    }

    // ── SUBIR Y GUARDAR UN LOGO NUEVO EN LA GALERÍA DEL CLIENTE
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|image|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $clienteId = session('usuario_id');
        $archivo   = $request->file('archivo');
        $ruta      = $archivo->storeAs(
            'logos_clientes/' . $clienteId,
            Str::uuid() . '.' . $archivo->getClientOriginalExtension(),
            'public'
        );

        $logo = Logo::create([
            'cliente_id' => $clienteId,
            'nombre'     => $archivo->getClientOriginalName(),
            'ruta'       => $ruta,
        ]);

        return response()->json([
            'success' => true,
            'logo'    => [
                'id'     => $logo->id,
                'nombre' => $logo->nombre,
                'url'    => asset('storage/' . $logo->ruta),
            ],
        ]);
    }

    // ── ELIMINAR UN LOGO DE LA GALERÍA (solo si pertenece al cliente actual)
    public function destroy($id)
    {
        $clienteId = session('usuario_id');
        $logo = Logo::where('cliente_id', $clienteId)->findOrFail($id);

        Storage::disk('public')->delete($logo->ruta);
        $logo->delete();

        return response()->json(['success' => true]);
    }
}
