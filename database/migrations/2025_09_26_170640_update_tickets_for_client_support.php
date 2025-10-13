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
            // Cambiar created_by a client_id para mejor semántica
            $table->renameColumn('created_by', 'client_id');
            
            // Actualizar estados posibles - primero eliminar el enum actual
            $table->dropColumn('estado');
        });
        
        // En una segunda operación agregar el nuevo enum
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('estado', ['abierto', 'en_seguimiento', 'pausado', 'cerrado'])->default('abierto')->after('descripcion');
            
            // Agregar campos para tracking
            $table->timestamp('started_at')->nullable(); // Cuándo se inició el trabajo
            $table->integer('total_time_minutes')->default(0); // Tiempo total acumulado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Revertir cambios
            $table->renameColumn('client_id', 'created_by');
            $table->dropColumn(['started_at', 'total_time_minutes', 'estado']);
        });
        
        Schema::table('tickets', function (Blueprint $table) {
            // Restaurar enum original
            $table->enum('estado', ['abierto', 'en_progreso', 'resuelto', 'cerrado'])->default('abierto')->after('descripcion');
        });
    }
};
