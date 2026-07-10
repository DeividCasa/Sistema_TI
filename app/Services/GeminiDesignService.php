<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/*
 * Traduce el prompt libre del cliente en datos estructurados (colores por
 * zona / rayas / figuras simples / sugerencias de elementos gráficos) que
 * el editor aplica directo sobre el canvas 2D y el modelo 3D reales.
 *
 * Solo usa el modelo de texto de Gemini: el modelo de generación de
 * imágenes de Gemini no está disponible en el nivel gratuito (pide
 * facturación habilitada en el proyecto), así que los elementos gráficos
 * complejos (ej. un escudo) no se generan como imagen — se devuelven como
 * sugerencia en texto para que el cliente los agregue manualmente desde la
 * pestaña Logo.
 */
class GeminiDesignService
{
    private const MODELO_TEXTO = 'gemini-2.5-flash';
    private const BASE_URL     = 'https://generativelanguage.googleapis.com/v1beta/models/';

    // Espejo exacto de las claves key3d de PRENDAS.*.vistas en
    // public/js/personalizar/prendas.js — si esas claves cambian, actualizar aquí también.
    public function zonasPorPrenda(string $tipoPrenda): array
    {
        return $tipoPrenda === 'chompa'
            ? [
                'chompaColorFrente', 'chompaColorAtras', 'chompaColorMangas', 'chompaColorMangaIzq',
                'chompaColorCierre', 'chompaColorBolsillo', 'chompaColorCapucha', 'chompaColorParteAbajo',
            ]
            : [
                'colorFrente', 'colorAtras', 'colorMangas', 'colorParteAbajoMangas',
                'colorCuello', 'colorParteAbajoCamiseta',
            ];
    }

    public function interpretarPrompt(string $prompt, array $zonasValidas): array
    {
        $apiKey = config('services.gemini.key');

        $propiedadesColores = [];
        foreach ($zonasValidas as $zona) {
            $propiedadesColores[$zona] = ['type' => 'STRING'];
        }

        $schema = [
            'type' => 'OBJECT',
            'properties' => [
                'colores' => [
                    'type' => 'OBJECT',
                    'properties' => $propiedadesColores,
                    'required' => $zonasValidas,
                ],
                'rayas' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'activo'    => ['type' => 'BOOLEAN'],
                        'color'     => ['type' => 'STRING'],
                        'cantidad'  => ['type' => 'INTEGER'],
                        'direccion' => ['type' => 'STRING', 'enum' => ['horizontal', 'vertical']],
                        'zonas'     => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING', 'enum' => ['frente', 'atras']],
                        ],
                    ],
                    'required' => ['activo', 'color', 'cantidad', 'direccion', 'zonas'],
                ],
                'figuras' => [
                    'type' => 'ARRAY',
                    'items' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'tipo'  => ['type' => 'STRING', 'enum' => ['heart', 'star', 'circle', 'rect', 'triangle']],
                            'color' => ['type' => 'STRING'],
                            'zona'  => ['type' => 'STRING', 'enum' => ['frente', 'atras']],
                        ],
                        'required' => ['tipo', 'color', 'zona'],
                    ],
                ],
                'elementos_sugeridos' => [
                    'type' => 'ARRAY',
                    'items' => ['type' => 'STRING'],
                ],
            ],
            'required' => ['colores', 'rayas', 'figuras', 'elementos_sugeridos'],
        ];

        $instrucciones = "Eres un asistente que traduce la descripción en texto de un cliente sobre "
            . "cómo quiere personalizar una prenda deportiva, en datos estructurados para un editor "
            . "de diseño. Debes devolver colores en formato hexadecimal (#rrggbb) para cada una de "
            . "estas zonas exactas de la prenda: " . implode(', ', $zonasValidas) . ". Si el cliente "
            . "no menciona una zona específica, usa un color coherente con el diseño general (o el "
            . "mismo color base). Si el diseño incluye rayas, marca rayas.activo=true, indica el color, "
            . "la 'cantidad' exacta de rayas que pidió el cliente (si no la menciona, usa 4), la "
            . "'direccion' ('horizontal' u 'vertical' — respeta literalmente lo que el cliente pida; "
            . "si no lo aclara, usa 'horizontal' salvo que el diseño sea claramente de un equipo/estilo "
            . "conocido por rayas verticales) y en qué paneles van ('frente', 'atras' o ambos); si no "
            . "hay rayas, usa activo=false. En 'figuras' lista formas simples que el cliente pidió y que "
            . "coinciden EXACTAMENTE con uno de estos tipos dibujables: 'heart' (corazón), 'star' "
            . "(estrella), 'circle' (círculo), 'rect' (cuadro/rectángulo), 'triangle' (triángulo) — con "
            . "su color en hexadecimal y en qué panel va ('frente' o 'atras'); NO inventes un tipo fuera "
            . "de esa lista. En 'elementos_sugeridos' lista, en español y en máximo 2 frases cortas, "
            . "cualquier gráfico complejo que el cliente pidió pero que NO es una de las formas simples "
            . "de 'figuras' (escudos, emblemas, íconos, letras estilizadas, etc.) — esto es solo una "
            . "sugerencia de texto para que el cliente lo suba como imagen aparte, no se genera ningún "
            . "gráfico. Si no aplica, devuelve arreglos vacíos.\n\n"
            . "Descripción del cliente: \"" . $prompt . "\"";

        $response = Http::withHeaders(['x-goog-api-key' => $apiKey])
            ->timeout(45)
            ->post(self::BASE_URL . self::MODELO_TEXTO . ':generateContent', [
                'contents' => [
                    ['parts' => [['text' => $instrucciones]]],
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'responseSchema'   => $schema,
                ],
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Gemini no pudo interpretar el diseño: ' . $response->body());
        }

        $texto = $response->json('candidates.0.content.parts.0.text');
        $datos = json_decode((string) $texto, true);

        if (!is_array($datos)) {
            throw new RuntimeException('Gemini devolvió una respuesta sin JSON válido.');
        }

        // Filtro defensivo: nunca dejar pasar una clave de zona que el
        // frontend no reconozca, ni un tipo de figura que el canvas no sepa dibujar.
        $datos['colores'] = array_intersect_key($datos['colores'] ?? [], array_flip($zonasValidas));
        $datos['elementos_sugeridos'] = array_slice($datos['elementos_sugeridos'] ?? [], 0, 2);

        $tiposFigura = ['heart', 'star', 'circle', 'rect', 'triangle'];
        $datos['figuras'] = array_slice(array_values(array_filter(
            $datos['figuras'] ?? [],
            fn ($f) => in_array($f['tipo'] ?? null, $tiposFigura, true)
        )), 0, 3);

        if (!empty($datos['rayas']['activo'])) {
            $datos['rayas']['cantidad']  = max(1, min(12, (int) ($datos['rayas']['cantidad'] ?? 4)));
            $datos['rayas']['direccion'] = ($datos['rayas']['direccion'] ?? 'horizontal') === 'vertical'
                ? 'vertical' : 'horizontal';
        }

        return $datos;
    }
}
