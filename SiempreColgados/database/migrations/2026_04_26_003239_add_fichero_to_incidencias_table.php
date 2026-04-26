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
        Schema::table('incidencias', function (Blueprint $table) {
            // Añadir columna 'fichero' para almacenar la ruta del archivo adjunto
            $table->string('fichero', 255)->nullable()->after('fecha_realizacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Eliminar columna si se revierte la migración
            $table->dropColumn('fichero');
        });
    }
};
