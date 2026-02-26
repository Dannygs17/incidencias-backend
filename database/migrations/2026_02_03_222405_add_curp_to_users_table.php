<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        // Agregamos la columna CURP
        // unique() -> Impide que dos personas tengan la misma CURP
        // nullable() -> Evita errores si ya tenías usuarios creados antes
        $table->string('curp')->unique()->nullable()->after('email');
    });
    }
};
