<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uniforme_tallas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uniforme_id')->constrained('uniformes')->onDelete('cascade');
            $table->string('talla', 10);          // 32, 34, 36, 38, 40, 42...
            $table->decimal('precio', 10, 2);     // cada talla con su precio
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->unique(['uniforme_id', 'talla']); // no repetir talla en el mismo uniforme
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uniforme_tallas');
    }
};
