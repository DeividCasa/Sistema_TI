<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes_uniforme', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_uniforme_id')->constrained('pedidos_uniforme')->onDelete('cascade');
            $table->enum('tipo', ['adelanto', 'pago_completo', 'saldo_final']);
            $table->string('archivo', 255);              // imagen del voucher
            $table->string('referencia', 100)->nullable();
            $table->decimal('monto', 10, 2);
            $table->enum('estado', ['pendiente','verificado','rechazado'])->default('pendiente');
            $table->text('nota_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes_uniforme');
    }
};
