<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comprobantes_plantilla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_plantilla_id')->constrained('pedidos_plantilla')->onDelete('cascade');
            $table->enum('tipo', ['adelanto', 'pago_completo', 'saldo_final']);
            $table->string('archivo', 255);
            $table->string('referencia', 100)->nullable();
            $table->decimal('monto', 10, 2);
            $table->enum('estado', ['pendiente','verificado','rechazado'])->default('pendiente');
            $table->text('nota_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes_plantilla');
    }
};
