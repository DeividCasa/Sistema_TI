<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones_vistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('administradores')->cascadeOnDelete();
            $table->string('seccion');
            $table->timestamp('visto_at')->nullable();
            $table->timestamps();

            $table->unique(['admin_id', 'seccion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones_vistas');
    }
};
