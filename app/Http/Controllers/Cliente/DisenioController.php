<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Disenio;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DisenioController extends Controller
{
    // ── MOSTRAR PANTALLA "PERSONALIZAR / CREAR DISEÑO"
    public function create($plantillaId = null)
    {
        $plantilla = $plantillaId ? Plantilla::findOrFail($plantillaId) : null;

        return view('cliente.personalizar', compact('plantilla'));
    }

    // ── GUARDAR DISEÑO HECHO A MANO (colores, texto, posición, captura 2D)
    public function store(Request $request)
    {
        $request->merge(['plantilla_id' => $request->plantilla_id ?: null]);

        $request->validate([
            'plantilla_id'    => 'nullable|exists:plantillas,id',
            'nombre'          => 'nullable|string|max:150',
            'tipo_prenda'     => 'required|string',
            'color_principal' => 'required|string|max:20',
            'color_secundario'=> 'nullable|string|max:20',
            'color_terciario' => 'nullable|string|max:20',
            'texto'           => 'nullable|string|max:40',
            'texto_color'     => 'nullable|string|max:20',
            'texto_pos_x'     => 'nullable|numeric',
            'texto_pos_y'     => 'nullable|numeric',
            'logo_pos_x'      => 'nullable|numeric',
            'logo_pos_y'      => 'nullable|numeric',
            'imagen_captura'  => 'nullable|string', // viene como data:image/png;base64,....
        ]);

        $clienteId = session('usuario_id');

        // ── Convertimos la captura 2D (que llega en base64 desde el canvas 3D)
        //    en un archivo PNG real guardado en storage, igual que cualquier otra imagen.
        $rutaImagen = null;
        if ($request->filled('imagen_captura')) {
            $rutaImagen = $this->guardarCapturaBase64($request->imagen_captura);
        }

        $disenio = Disenio::create([
            'cliente_id'   => $clienteId,
            'plantilla_id' => $request->plantilla_id,
            'nombre'       => $request->nombre ?: 'Mi diseño',
            'configuracion' => [
                'tipo_prenda'      => $request->tipo_prenda,
                'color_principal'  => $request->color_principal,
                'color_secundario' => $request->color_secundario,
                'color_terciario'  => $request->color_terciario,
                'texto'            => $request->texto,
                'texto_color'      => $request->texto_color,
                // Posición exacta donde el cliente dejó el texto y el logo sobre la prenda
                'texto_pos' => [
                    'x' => $request->texto_pos_x,
                    'y' => $request->texto_pos_y,
                ],
                'logo_pos' => [
                    'x' => $request->logo_pos_x,
                    'y' => $request->logo_pos_y,
                ],
            ],
            // La captura 2D generada desde el visor 3D se guarda aquí, igual que
            // funcionan los diseños generados por IA — así todo el catálogo y
            // "Mis pedidos" siempre muestra fotos 2D, sin importar el origen.
            'imagen_generada' => $rutaImagen,
            'origen' => $request->plantilla_id ? 'plantilla' : 'manual',
        ]);

        return response()->json([
            'success'    => true,
            'disenio_id' => $disenio->id,
            'imagen_url' => $rutaImagen ? asset('storage/' . $rutaImagen) : null,
            'message'    => '¡Diseño guardado correctamente!',
        ]);
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

    // ── GENERAR DISEÑO CON IA A PARTIR DE UN PROMPT
    public function generarIA(Request $request)
    {
        $request->merge(['plantilla_id' => $request->plantilla_id ?: null]);

        $request->validate([
            'prompt'       => 'required|string|min:5|max:500',
            'plantilla_id' => 'nullable|exists:plantillas,id',
        ]);

        $apiKey = config('services.openai.key');

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
            $promptFinal = 'Diseño de uniforme deportivo / prenda textil, vista frontal, fondo blanco, '
                         . 'estilo catálogo de producto, sin maniquí ni persona. Detalles: '
                         . $request->prompt;

            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->post('https://api.openai.com/v1/images/generations', [
                    'model'  => 'gpt-image-1',
                    'prompt' => $promptFinal,
                    'size'   => '1024x1024',
                    'n'      => 1,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo generar el diseño en este momento. Intenta de nuevo.',
                ], 200);
            }

            $data = $response->json('data.0');

            // La API puede devolver la imagen en base64 (b64_json) o una URL temporal.
            if (isset($data['b64_json'])) {
                $contenido = base64_decode($data['b64_json']);
            } elseif (isset($data['url'])) {
                $contenido = Http::timeout(30)->get($data['url'])->body();
            } else {
                throw new \Exception('Respuesta de IA sin imagen.');
            }

            $ruta = 'disenios_ia/' . Str::uuid() . '.png';
            Storage::disk('public')->put($ruta, $contenido);

            $clienteId = session('usuario_id');

            $disenio = Disenio::create([
                'cliente_id'     => $clienteId,
                'plantilla_id'   => $request->plantilla_id,
                'nombre'         => 'Diseño IA',
                'configuracion'  => ['prompt' => $request->prompt],
                'imagen_generada'=> $ruta,
                'origen'         => 'ia',
            ]);

            return response()->json([
                'success'    => true,
                'disenio_id' => $disenio->id,
                'imagen_url' => asset('storage/' . $ruta),
                'message'    => '¡Tu diseño fue generado con IA!',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error generando el diseño con IA. Intenta de nuevo más tarde.',
            ], 200);
        }
    }
}