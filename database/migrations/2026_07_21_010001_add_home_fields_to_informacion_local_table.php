<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->string('banner_titulo')->nullable();
            $table->string('banner_subtitulo')->nullable();
            $table->string('categoria_uniforme_imagen')->nullable();
            $table->string('categoria_chompa_imagen')->nullable();
            $table->string('categoria_ropa_imagen')->nullable();
            $table->string('whatsapp_numero')->nullable();
            $table->string('whatsapp_mensaje')->nullable();
            $table->string('whatsapp_direccion')->nullable();
            $table->string('whatsapp_horario')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->dropColumn([
                'banner_titulo',
                'banner_subtitulo',
                'categoria_uniforme_imagen',
                'categoria_chompa_imagen',
                'categoria_ropa_imagen',
                'whatsapp_numero',
                'whatsapp_mensaje',
                'whatsapp_direccion',
                'whatsapp_horario',
            ]);
        });
    }
};
