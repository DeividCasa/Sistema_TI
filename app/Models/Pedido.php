<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'disenio_id',
        'codigo',
        'cantidad_total',
        'precio_total',
        'precio_adelanto',
        'precio_saldo',
        'estado',
        'estado_pago',
        'observaciones',
        'tiempo_estimado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function disenio()
    {
        return $this->belongsTo(Disenio::class, 'disenio_id');
    }

    public function tallas()
    {
        return $this->hasMany(TallaPedido::class, 'pedido_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobantePago::class, 'pedido_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstado::class, 'pedido_id');
    }
}