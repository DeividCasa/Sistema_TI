<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_chompa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('codigo', 30)->unique();       // PCH-20260001
            $table->integer('cantidad_total')->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->decimal('precio_adelanto', 10, 2)->default(0);  // 50%
            $table->decimal('precio_saldo', 10, 2)->default(0);
            $table->enum('estado', [
                'recibido',
                'en_produccion',
                'listo',
                'enviado',
                'entregado',
                'cancelado',
            ])->default('recibido');
            $table->enum('estado_pago', [
                'pendiente',
                'adelanto_verificado',
                'pagado_completo',
            ])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_chompa');
    }
};
