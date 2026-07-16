<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disenio extends Model
{
    protected $table = 'disenios';

    protected $fillable = [
        'cliente_id',
        'plantilla_id',
        'genero',
        'nombre',
        'configuracion',
        'imagen_generada',
        'imagen_atras',
        'origen',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'plantilla_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'disenio_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudDiseno::class, 'disenio_id');
    }
}