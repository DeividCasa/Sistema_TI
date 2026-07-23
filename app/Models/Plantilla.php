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
        'precio',
        'colores',
        'tallas',
        'archivo_3d',
        'imagen_preview',
        'activa',
        'destacado',
    ];

    protected $casts = [
        'colores' => 'array',
        'tallas'  => 'array',
    ];

    public function disenios()
    {
        return $this->hasMany(Disenio::class, 'plantilla_id');
    }
}