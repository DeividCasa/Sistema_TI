<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    protected $fillable = [
        'nombre',
        'tipo_prenda',
        'genero',
        'descripcion',
        'tela',
        'archivo_3d',
        'imagen_preview',
        'activa',
        'destacado',
    ];

    public function disenios()
    {
        return $this->hasMany(Disenio::class, 'plantilla_id');
    }

    // Alias para que el partial compartido "producto-detalle-tallas" (usado
    // también por Chompa/Uniforme, que sí tienen columna "imagen") funcione
    // sin cambios con Plantilla, cuya foto vive en "imagen_preview".
    public function getImagenAttribute()
    {
        return $this->imagen_preview;
    }

    public function tallas()
    {
        // Ordenar: primero tallas numéricas (32,34...) luego alfas (XS,S,M,L,XL,XXL)
        return $this->hasMany(PlantillaTalla::class, 'plantilla_id')
                    ->orderByRaw("CASE WHEN talla REGEXP '^[0-9]+$' THEN CAST(talla AS UNSIGNED) ELSE 999 END, talla");
    }
}
