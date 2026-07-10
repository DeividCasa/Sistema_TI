<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_chompa_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_chompa_id')->constrained('pedidos_chompa')->onDelete('cascade');
            $table->foreignId('chompa_id')->constrained('chompas')->onDelete('cascade');
            $table->foreignId('chompa_talla_id')->nullable()->constrained('chompa_tallas')->onDelete('set null');
            $table->string('talla', 10);
            $table->decimal('precio_unitario', 10, 2);
            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_chompa_items');
    }
};
