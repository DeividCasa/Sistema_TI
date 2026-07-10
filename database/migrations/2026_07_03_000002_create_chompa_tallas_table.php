<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chompa_tallas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chompa_id')->constrained('chompas')->onDelete('cascade');
            $table->string('talla', 10);          // XS, S, M, L, XL, XXL, 32, 34...
            $table->decimal('precio', 10, 2);     // cada talla con su propio precio
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->unique(['chompa_id', 'talla']); // no repetir talla en la misma chompa
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chompa_tallas');
    }
};
