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
        Schema::create('ticket_time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users')->onDelete('cascade'); // Técnico
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->enum('status', ['started', 'paused', 'stopped'])->default('started');
            $table->text('comment')->nullable(); // Comentario al pausar o finalizar
            $table->integer('duration_minutes')->default(0); // Duración en minutos calculada
            $table->timestamps();
            
            $table->index(['ticket_id', 'technician_id', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_time_entries');
    }
};
