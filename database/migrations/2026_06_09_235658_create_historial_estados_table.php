<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('administradores')->onDelete('set null');
            $table->enum('estado_anterior', ['recibido','en_produccion','listo','enviado','entregado','cancelado'])->nullable();
            $table->enum('estado_nuevo', ['recibido','en_produccion','listo','enviado','entregado','cancelado']);
            $table->text('nota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
    }
};