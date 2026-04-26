<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración tabla empleados
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
return new class extends Migration
{
    /**
     * Crea la tabla empleados.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 9)->unique();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_alta');
            $table->enum('tipo', ['operario', 'administrador'])->default('operario');
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
