<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración tabla clientes
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
return new class extends Migration
{
    /**
     * Crea la tabla clientes.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cif', 9)->unique();
            $table->string('nombre');
            $table->string('telefono', 20)->nullable();
            $table->string('correo')->nullable();
            $table->string('cuenta_corriente', 34)->nullable();
            $table->string('pais', 2)->default('ES');
            $table->string('moneda', 3)->default('EUR');
            $table->decimal('importe_cuota', 10, 2)->default(0.00);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
