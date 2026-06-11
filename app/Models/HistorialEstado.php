<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    protected $fillable = [
        'pedido_id',
        'admin_id',
        'estado_anterior',
        'estado_nuevo',
        'nota',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function administrador()
    {
        return $this->belongsTo(Administrador::class, 'admin_id');
    }
}