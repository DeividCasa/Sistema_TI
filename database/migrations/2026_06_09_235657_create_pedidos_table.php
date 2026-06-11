<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('disenio_id')->constrained('disenios')->onDelete('cascade');
            $table->string('codigo', 20)->unique();
            $table->integer('cantidad_total')->unsigned()->default(1);
            $table->decimal('precio_total', 10, 2);
            $table->decimal('precio_adelanto', 10, 2);
            $table->decimal('precio_saldo', 10, 2);
            $table->enum('estado', ['recibido','en_produccion','listo','enviado','entregado','cancelado'])->default('recibido');
            $table->enum('estado_pago', ['pendiente','adelanto_enviado','adelanto_verificado','saldo_pendiente','pagado_completo'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};