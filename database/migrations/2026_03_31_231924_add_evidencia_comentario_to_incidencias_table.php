<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Agregamos las dos nuevas columnas permitiendo que estén vacías (nullable)
            $table->text('comentario_admin')->nullable()->after('estado');
            $table->string('evidencia_path')->nullable()->after('comentario_admin');
        });
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropColumn(['comentario_admin', 'evidencia_path']);
        });
    }
};