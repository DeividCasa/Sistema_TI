<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'email',
        'password',
        'telefono',
        'direccion',
        'ciudad',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = $value === null ? $value : mb_strtoupper(trim($value));
    }

    public function setApellidoAttribute($value)
    {
        $this->attributes['apellido'] = $value === null ? $value : mb_strtoupper(trim($value));
    }

    public function setCiudadAttribute($value)
    {
        $this->attributes['ciudad'] = $value === null ? $value : mb_strtoupper(trim($value));
    }

    public function disenios()
    {
        return $this->hasMany(Disenio::class, 'cliente_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function pedidosUniforme()
    {
        return $this->hasMany(PedidoUniforme::class, 'cliente_id');
    }

    public function pedidosChompa()
    {
        return $this->hasMany(PedidoChompa::class, 'cliente_id');
    }

    public function pedidosPlantilla()
    {
        return $this->hasMany(PedidoPlantilla::class, 'cliente_id');
    }

    public function pedidosMaestro()
    {
        return $this->hasMany(PedidoMaestro::class, 'cliente_id');
    }
}