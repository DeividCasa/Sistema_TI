<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoPlantillaItem extends Model
{
    protected $table = 'pedido_plantilla_items';

    protected $fillable = [
        'pedido_plantilla_id',
        'plantilla_id',
        'plantilla_talla_id',
        'talla',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoPlantilla::class, 'pedido_plantilla_id');
    }

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'plantilla_id');
    }

    public function plantillaTalla()
    {
        return $this->belongsTo(PlantillaTalla::class, 'plantilla_talla_id');
    }
}
