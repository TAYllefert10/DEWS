<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Cambiar la columna 'estado' para aceptar todos los valores válidos
            $table->string('estado', 1)->default('P')->change();
            // O si prefieres ENUM explícito:
            // $table->enum('estado', ['P', 'E', 'R', 'C'])->default('P')->change();
        });
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Revertir al estado anterior (ajusta según tu migración original)
            $table->enum('estado', ['P', 'R', 'C'])->default('P')->change();
        });
    }
};
