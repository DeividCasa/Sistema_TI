<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->unsignedBigInteger('plantilla_id')->nullable(); // ← sin constrained
            $table->string('nombre', 150)->default('Mi diseño');
            $table->json('configuracion')->nullable();
            $table->string('imagen_generada', 255)->nullable();
            $table->enum('origen', ['manual','plantilla','ia'])->default('manual');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disenios');
    }
};