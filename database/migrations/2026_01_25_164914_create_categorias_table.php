<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('nombre')->unique(); // El nombre de la categoría
            $blueprint->boolean('activa')->default(true); // Para poder "desactivar" categorías sin borrarlas
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};