<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración tablas incidencias y cuotas
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
return new class extends Migration
{
    /**
     * Crea las tablas incidencias y cuotas.
     */
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('operario_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->string('persona_contacto', 150);
            $table->string('telefono_contacto', 20);
            $table->string('descripcion', 500);
            $table->string('correo');
            $table->string('direccion', 200)->nullable();
            $table->string('poblacion', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->string('provincia_codigo', 2)->nullable();
            $table->enum('estado', ['P', 'R', 'C'])->default('P');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->date('fecha_realizacion')->nullable();
            $table->text('anotaciones_anteriores')->nullable();
            $table->text('anotaciones_posteriores')->nullable();
            $table->string('fichero_resumen')->nullable();
            $table->timestamps();
        });

        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('concepto', 200);
            $table->date('fecha_emision');
            $table->decimal('importe', 10, 2);
            $table->string('moneda', 3)->default('EUR');
            $table->boolean('pagada')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->decimal('importe_euros', 10, 2)->nullable();
            $table->decimal('tipo_cambio', 10, 6)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
        Schema::dropIfExists('incidencias');
    }
};
