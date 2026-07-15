<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_maestro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('codigo', 20)->unique();       // PED-2026-0001
            $table->decimal('precio_total', 10, 2);
            $table->decimal('precio_adelanto', 10, 2);    // 50%
            $table->decimal('precio_saldo', 10, 2);       // 50% restante
            $table->enum('estado_pago', ['pendiente','adelanto_enviado','adelanto_verificado','pago_completo_enviado','saldo_enviado','pagado_completo'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_maestro');
    }
};
