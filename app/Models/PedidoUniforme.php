<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoUniforme extends Model
{
    protected $table = 'pedidos_uniforme';

    protected $fillable = [
        'cliente_id',
        'pedido_maestro_id',
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

    public function pedidoMaestro()
    {
        return $this->belongsTo(PedidoMaestro::class, 'pedido_maestro_id');
    }

    public function items()
    {
        return $this->hasMany(PedidoUniformeItem::class, 'pedido_uniforme_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobanteUniforme::class, 'pedido_uniforme_id');
    }
}
