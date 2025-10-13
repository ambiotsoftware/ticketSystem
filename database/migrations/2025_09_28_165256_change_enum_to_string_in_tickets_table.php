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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('estado')->change();
            $table->string('prioridad')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Para que la migración sea reversible, definimos los enums originales.
            // Nota: Esto es solo por completitud, no se recomienda volver a enums de DB.
            $table->enum('estado', ['abierto', 'en_progreso', 'resuelto', 'cerrado', 'en_seguimiento', 'pausado'])->default('abierto')->change();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media')->change();
        });
    }
};
