<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoUniformeItem extends Model
{
    protected $table = 'pedido_uniforme_items';

    protected $fillable = [
        'pedido_uniforme_id',
        'uniforme_id',
        'uniforme_talla_id',
        'talla',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoUniforme::class, 'pedido_uniforme_id');
    }

    public function uniforme()
    {
        return $this->belongsTo(Uniforme::class, 'uniforme_id');
    }

    public function tallaUniforme()
    {
        return $this->belongsTo(UniformeTalla::class, 'uniforme_talla_id');
    }
}
