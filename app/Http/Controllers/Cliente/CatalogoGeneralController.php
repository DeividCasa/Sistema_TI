<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Chompa;
use App\Models\Plantilla;
use App\Models\Uniforme;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogoGeneralController extends Controller
{
    const POR_PAGINA = 16;

    public function index(Request $request)
    {
        $todos = $this->obtenerTodosLosProductos();

        $categoria = $request->query('categoria', 'todos');
        $talla     = $request->query('talla', 'todos');
        $genero    = $request->query('genero', 'todos');
        $precioMin = $request->query('precio_min');
        $precioMax = $request->query('precio_max');
        $texto     = trim((string) $request->query('q', ''));
        $offset    = max(0, (int) $request->query('offset', 0));

        $filtrados = $todos->filter(function ($item) use ($categoria, $talla, $genero, $precioMin, $precioMax, $texto) {
            if ($categoria !== 'todos' && $item['tipo'] !== $categoria) return false;
            if ($talla !== 'todos' && !in_array($talla, $item['tallas'], true)) return false;
            if ($genero !== 'todos' && $item['genero'] !== $genero) return false;
            if ($precioMin !== null && $item['precio'] < (float) $precioMin) return false;
            if ($precioMax !== null && $item['precio'] > (float) $precioMax) return false;
            if ($texto !== '' && !str_contains(strtolower($item['nombre']), strtolower($texto))) return false;
            return true;
        })->values();

        $total  = $filtrados->count();
        $pagina = $filtrados->slice($offset, self::POR_PAGINA)->values();
        $mostrados = min($offset + self::POR_PAGINA, $total);

        // Recordamos la última vista del catálogo (con sus filtros) para que los
        // botones "Volver al catálogo" / "Seguir comprando" regresen aquí, incluso
        // después de pasar por un agregar-al-carrito (POST) o un cambio de filtro (AJAX).
        session(['catalogo_url' => route('cliente.catalogo.index', $request->except(['fragmento', 'offset']))]);

        if ($request->query('fragmento')) {
            return response()->json([
                'html'      => view('cliente.catalogo_general._grid', ['productos' => $pagina])->render(),
                'total'     => $total,
                'mostrados' => $mostrados,
            ]);
        }

        $precios = $todos->pluck('precio');
        $precioGlobalMin = $precios->isNotEmpty() ? (int) floor($precios->min()) : 0;
        $precioGlobalMax = $precios->isNotEmpty() ? (int) ceil($precios->max()) : 0;
        if ($precioGlobalMax <= $precioGlobalMin) $precioGlobalMax = $precioGlobalMin + 1;

        $tallasDisponibles = $todos->flatMap(fn ($item) => $item['tallas'])->unique()->sort()->values();

        return view('cliente.catalogo_general.index', [
            'productos'         => $pagina,
            'total'             => $total,
            'mostrados'         => $mostrados,
            'tallasDisponibles' => $tallasDisponibles,
            'precioGlobalMin'   => $precioGlobalMin,
            'precioGlobalMax'   => $precioGlobalMax,
            'categoriaActiva'   => $categoria,
            'tallaActiva'       => $talla,
            'generoActivo'      => $genero,
            'precioMinActivo'   => $precioMin !== null ? (float) $precioMin : $precioGlobalMin,
            'precioMaxActivo'   => $precioMax !== null ? (float) $precioMax : $precioGlobalMax,
        ]);
    }

    private function obtenerTodosLosProductos(): Collection
    {
        $plantillas = Plantilla::where('activa', 1)->get()->map(function ($p) {
            return [
                'id'     => 'plantilla-' . $p->id,
                'tipo'   => $p->tipo_prenda,
                'genero' => $p->genero,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio,
                'tallas' => collect($p->tallas ?? [])->map(fn ($t) => strtolower($t))->values()->all(),
                'imagen' => $p->imagen_preview ? asset('storage/' . $p->imagen_preview) : null,
                'url'    => route('producto.ver', $p->id),
                'badge'  => ucfirst($p->tipo_prenda),
                'creado' => $p->created_at,
            ];
        });

        $uniformes = Uniforme::where('activo', 1)->with('tallas')->get()->map(function ($u) {
            $tallasDisp = $u->tallas->where('disponible', 1);
            $precio = $tallasDisp->pluck('precio')->min();
            return [
                'id'     => 'uniforme-' . $u->id,
                'tipo'   => 'uniforme',
                'genero' => $u->genero,
                'nombre' => $u->nombre,
                'precio' => $precio !== null ? (float) $precio : 0.0,
                'tallas' => $tallasDisp->pluck('talla')->map(fn ($t) => strtolower($t))->values()->all(),
                'imagen' => $u->imagen ? asset('storage/' . $u->imagen) : null,
                'url'    => route('cliente.uniformes.show', $u->id),
                'badge'  => 'Uniforme',
                'creado' => $u->created_at,
            ];
        });

        $chompas = Chompa::where('activo', 1)->with('tallas')->get()->map(function ($c) {
            $tallasDisp = $c->tallas->where('disponible', 1);
            $precio = $tallasDisp->pluck('precio')->min();
            return [
                'id'     => 'chompa-' . $c->id,
                'tipo'   => 'chompa',
                'genero' => $c->genero,
                'nombre' => $c->nombre,
                'precio' => $precio !== null ? (float) $precio : 0.0,
                'tallas' => $tallasDisp->pluck('talla')->map(fn ($t) => strtolower($t))->values()->all(),
                'imagen' => $c->imagen ? asset('storage/' . $c->imagen) : null,
                'url'    => route('cliente.chompas.show', $c->id),
                'badge'  => 'Chompa',
                'creado' => $c->created_at,
            ];
        });

        return $plantillas->concat($uniformes)->concat($chompas)->sortByDesc('creado')->values();
    }
}
