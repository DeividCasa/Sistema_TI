<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Disenio;
use App\Models\Plantilla;
use App\Services\GeminiDesignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DisenioController extends Controller
{
    // ── MOSTRAR PANTALLA "PERSONALIZAR / CREAR DISEÑO"
    public function create($plantillaId = null)
    {
        $plantilla = $plantillaId ? Plantilla::findOrFail($plantillaId) : null;

        return view('cliente.personalizar.index', compact('plantilla'));
    }

    // ── GUARDAR DISEÑO HECHO A MANO (colores, texto, posición, captura 2D)
    public function store(Request $request)
    {
        $request->merge(['plantilla_id' => $request->plantilla_id ?: null]);

        $request->validate([
            'plantilla_id'    => 'nullable|exists:plantillas,id',
            'nombre'          => 'nullable|string|max:150',
            'tipo_prenda'     => 'required|string',
            'color_frente'           => 'required|string|max:20',
            'color_atras'            => 'required|string|max:20',
            'color_manga_izquierda'  => 'required|string|max:20',
            'color_manga_derecha'    => 'required|string|max:20',
            'color_cuello'           => 'required|string|max:20',
            'color_cierre'           => 'nullable|string|max:20',
            'color_bolsillo'         => 'nullable|string|max:20',
            'color_capucha'          => 'nullable|string|max:20',
            'color_parte_abajo'      => 'nullable|string|max:20',
            'canvas_json'            => 'nullable|string',
            'texto'           => 'nullable|string|max:40',
            'texto_color'     => 'nullable|string|max:20',
            'texto_pos_x'     => 'nullable|numeric',
            'texto_pos_y'     => 'nullable|numeric',
            'logo_pos_x'      => 'nullable|numeric',
            'logo_pos_y'      => 'nullable|numeric',
            'imagen_captura'  => 'nullable|string', // viene como data:image/png;base64,....
            'imagen_3d_frente'=> 'nullable|string',
            'imagen_3d_atras' => 'nullable|string',
        ]);

        $clienteId = session('usuario_id');

        // ── Convertimos las capturas (canvas 2D + visor 3D frente/atrás), que
        //    llegan en base64, en archivos PNG reales guardados en storage.
        $rutaImagen = null;
        if ($request->filled('imagen_3d_frente')) {
            $rutaImagen = $this->guardarCapturaBase64($request->imagen_3d_frente);
        } elseif ($request->filled('imagen_captura')) {
            $rutaImagen = $this->guardarCapturaBase64($request->imagen_captura);
        }

        $rutaImagenAtras = null;
        if ($request->filled('imagen_3d_atras')) {
            $rutaImagenAtras = $this->guardarCapturaBase64($request->imagen_3d_atras);
        }

        $disenio = Disenio::create([
            'cliente_id'   => $clienteId,
            'plantilla_id' => $request->plantilla_id,
            'nombre'       => $request->nombre ?: 'Mi diseño',
            'configuracion' => [
                'tipo_prenda' => $request->tipo_prenda,
            
                'colores' => [
                    'frente' => $request->color_frente,
                    'atras' => $request->color_atras,
                    'manga_izquierda' => $request->color_manga_izquierda,
                    'manga_derecha' => $request->color_manga_derecha,
                    'cuello' => $request->color_cuello,
                    'cierre' => $request->color_cierre,
                    'bolsillo' => $request->color_bolsillo,
                    'capucha' => $request->color_capucha,
                    'parte_abajo' => $request->color_parte_abajo,
                ],
                'canvas_json' => $request->canvas_json,
            
                'texto' => $request->texto,
                'texto_color' => $request->texto_color,
            
                'texto_pos' => [
                    'x' => $request->texto_pos_x,
                    'y' => $request->texto_pos_y,
                ],
            
                'logo_pos' => [
                    'x' => $request->logo_pos_x,
                    'y' => $request->logo_pos_y,
                ],
            ],
            // La captura del frente (visor 3D) se guarda aquí, igual que
            // funcionan los diseños generados por IA — así todo el catálogo y
            // "Mis diseños"/"Mis pedidos" siempre muestra fotos, sin importar el origen.
            'imagen_generada' => $rutaImagen,
            'imagen_atras'    => $rutaImagenAtras,
            'origen' => $request->plantilla_id ? 'plantilla' : 'manual',
        ]);

        return response()->json([
            'success'    => true,
            'disenio_id' => $disenio->id,
            'imagen_url' => $rutaImagen ? asset('storage/' . $rutaImagen) : null,
            'message'    => '¡Diseño guardado correctamente!',
        ]);
    }

    // ── ELIMINAR UN DISEÑO GUARDADO (desde "Mis diseños")
    public function destroy($id)
    {
        $disenio = Disenio::where('cliente_id', session('usuario_id'))->findOrFail($id);

        if ($disenio->pedidos()->exists()) {
            return back()->with('error', 'No puedes eliminar un diseño que ya tiene un pedido asociado.');
        }

        if ($disenio->imagen_generada) {
            Storage::disk('public')->delete($disenio->imagen_generada);
        }
        if ($disenio->imagen_atras) {
            Storage::disk('public')->delete($disenio->imagen_atras);
        }

        $disenio->delete();

        return back()->with('success', 'Diseño eliminado correctamente.');
    }

    // ── Convierte un data:image/png;base64,... en archivo real dentro de storage
    private function guardarCapturaBase64(string $dataUrl): ?string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $tipo)) {
            return null;
        }

        $extension = $tipo[1] === 'jpeg' ? 'jpg' : $tipo[1];
        $datosBinarios = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));

        if ($datosBinarios === false) {
            return null;
        }

        $ruta = 'disenios_capturas/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($ruta, $datosBinarios);

        return $ruta;
    }

    // ── GENERAR DISEÑO CON IA A PARTIR DE UN PROMPT (colores/rayas listos
    //    para aplicar directo sobre el editor 2D/3D)
    public function generarIA(Request $request, GeminiDesignService $gemini)
    {
        $request->merge(['plantilla_id' => $request->plantilla_id ?: null]);

        $request->validate([
            'prompt'       => 'required|string|min:5|max:500',
            'tipo_prenda'  => 'required|string|in:camiseta,chompa',
            'plantilla_id' => 'nullable|exists:plantillas,id',
        ]);

        $apiKey = config('services.gemini.key');

        // ── Si todavía no se ha configurado la API key, respondemos
        //    de forma controlada para que el front lo muestre como aviso.
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'pending' => true,
                'message' => 'La generación de diseños con IA aún no está configurada. '
                           . 'Pronto estará disponible — mientras tanto puedes personalizar tu diseño manualmente.',
            ], 200);
        }

        try {
            $zonasValidas = $gemini->zonasPorPrenda($request->tipo_prenda);
            $datos = $gemini->interpretarPrompt($request->prompt, $zonasValidas);

            // No se crea un Disenio aquí: esto solo devuelve la sugerencia
            // para aplicarla en el editor. El diseño recién se guarda de
            // verdad (con sus capturas) cuando el cliente pulsa "Guardar".
            return response()->json([
                'success'             => true,
                'colores'             => $datos['colores'],
                'rayas'               => $datos['rayas'],
                'figuras'             => $datos['figuras'],
                'elementos_sugeridos' => $datos['elementos_sugeridos'],
                'message'             => '¡Tu diseño fue generado con IA!',
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error generando el diseño con IA. Intenta de nuevo más tarde.',
            ], 200);
        }
    }
}
