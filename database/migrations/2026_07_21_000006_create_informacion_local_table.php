<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informacion_local', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_local')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('direccion')->nullable();
            $table->string('horario')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_contacto')->nullable();
            $table->string('imagen_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informacion_local');
    }
};
