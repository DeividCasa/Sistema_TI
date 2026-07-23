<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoPlantilla extends Model
{
    protected $table = 'pedidos_plantilla';

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
        return $this->hasMany(PedidoPlantillaItem::class, 'pedido_plantilla_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobantePlantilla::class, 'pedido_plantilla_id');
    }
}
