<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudDiseno extends Model
{
    protected $table = 'solicitudes_diseno';

    protected $fillable = [
        'cliente_id',
        'disenio_id',
        'pedido_id',
        'genero',
        'tela',
        'descripcion',
        'precio',
        'mensaje_admin',
        'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function disenio()
    {
        return $this->belongsTo(Disenio::class, 'disenio_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function tallas()
    {
        return $this->hasMany(TallaSolicitud::class, 'solicitud_id');
    }
}
