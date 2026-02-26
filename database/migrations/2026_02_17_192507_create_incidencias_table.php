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
            
            // Categoría: Aquí guardaremos "agua" (y las que decidas mantener)
            // Puedes usar un ENUM si la lista es fija, o string si planeas expandirla.
            $table->enum('categoria', ['agua', 'alumbrado', 'bacheo','basura'])->default('agua');
            
            $table->text('descripcion')->nullable();
            
            // Para la funcionalidad de la foto (.photo-container)
            $table->string('imagen_path')->nullable();
            
            // Para la funcionalidad del mapa (Google Maps)
            // Usamos decimal con suficiente precisión para coordenadas GPS
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            
            // Estado del reporte
            $table->string('estado')->default('pendiente'); // pendiente, atendido, etc.
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};