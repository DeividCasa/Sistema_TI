<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoChompaItem extends Model
{
    protected $table = 'pedido_chompa_items';

    protected $fillable = [
        'pedido_chompa_id',
        'chompa_id',
        'chompa_talla_id',
        'talla',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoChompa::class, 'pedido_chompa_id');
    }

    public function chompa()
    {
        return $this->belongsTo(Chompa::class, 'chompa_id');
    }

    public function tallaChompa()
    {
        return $this->belongsTo(ChompaTalla::class, 'chompa_talla_id');
    }
}
