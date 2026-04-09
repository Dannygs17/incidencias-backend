<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            // Relación con el usuario que reporta
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // NUEVA RELACIÓN: Conectamos con la tabla de categorías
            // Usamos nullable() por si acaso, aunque lo ideal es que siempre tenga una
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            
            $table->text('descripcion')->nullable();
            
            // Para la funcionalidad de la foto
            $table->string('imagen_path')->nullable();
            
            // Para la funcionalidad del mapa (GPS)
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            
            // Estado del reporte: pendiente, en proceso, resuelto, etc.
            $table->string('estado')->default('pendiente'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};