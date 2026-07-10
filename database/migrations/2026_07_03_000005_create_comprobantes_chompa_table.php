<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes_chompa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_chompa_id')->constrained('pedidos_chompa')->onDelete('cascade');
            $table->enum('tipo', ['adelanto', 'saldo_final', 'pago_completo'])->default('adelanto');
            $table->string('archivo', 255);
            $table->string('referencia', 100)->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->enum('estado', ['pendiente', 'verificado', 'rechazado'])->default('pendiente');
            $table->text('nota_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes_chompa');
    }
};
