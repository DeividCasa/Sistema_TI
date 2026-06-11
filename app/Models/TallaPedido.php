<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallaPedido extends Model
{
    protected $table = 'tallas_pedido';

    protected $fillable = [
        'pedido_id',
        'talla',
        'cantidad',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}