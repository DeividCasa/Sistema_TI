<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoMaestro extends Model
{
    protected $table = 'pedidos_maestro';

    protected $fillable = [
        'cliente_id',
        'codigo',
        'precio_total',
        'precio_adelanto',
        'precio_saldo',
        'estado_pago',
        'tiempo_estimado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function pedidoUniforme()
    {
        return $this->hasOne(PedidoUniforme::class, 'pedido_maestro_id');
    }

    public function pedidoChompa()
    {
        return $this->hasOne(PedidoChompa::class, 'pedido_maestro_id');
    }

    public function pedidoPlantilla()
    {
        return $this->hasOne(PedidoPlantilla::class, 'pedido_maestro_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobanteMaestro::class, 'pedido_maestro_id');
    }
}
