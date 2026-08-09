<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionLocal extends Model
{
    protected $table = 'informacion_local';

    protected $fillable = [
        'nombre_local',
        'descripcion',
        'direccion',
        'mapa_lat',
        'mapa_lng',
        'horario',
        'telefono',
        'email_contacto',
        'imagen_path',
        'banner_titulo',
        'banner_subtitulo',
        'categoria_uniforme_imagen',
        'categoria_chompa_imagen',
        'categoria_ropa_imagen',
        'whatsapp_numero',
        'whatsapp_mensaje',
        'whatsapp_direccion',
        'whatsapp_horario',
        'visitanos_titulo',
        'visitanos_texto',
        'cuenta_banco',
        'cuenta_tipo',
        'cuenta_numero',
        'cuenta_titular',
        'cuenta_identificacion',
    ];

    public static function actual(): self
    {
        return self::firstOrCreate([], [
            'nombre_local' => 'Leo José',
            'descripcion'  => 'Personaliza y confecciona tu ropa a tu medida.',
        ]);
    }
}
