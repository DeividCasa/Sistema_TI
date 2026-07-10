<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_uniforme_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_uniforme_id')->constrained('pedidos_uniforme')->onDelete('cascade');
            $table->foreignId('uniforme_id')->constrained('uniformes');
            $table->foreignId('uniforme_talla_id')->constrained('uniforme_tallas');
            $table->string('talla', 10);
            $table->decimal('precio_unitario', 10, 2);   // precio de esa talla al momento de comprar
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_uniforme_items');
    }
};
