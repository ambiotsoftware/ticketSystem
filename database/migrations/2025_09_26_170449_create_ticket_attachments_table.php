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
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('time_entry_id')->nullable()->constrained('ticket_time_entries')->onDelete('cascade'); // Opcional
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // Usuario que subió el archivo
            $table->string('filename'); // Nombre original del archivo
            $table->string('file_path'); // Ruta del archivo en storage
            $table->string('file_type', 50); // Tipo MIME
            $table->integer('file_size'); // Tamaño en bytes
            $table->text('description')->nullable(); // Descripción opcional
            $table->timestamps();
            
            $table->index(['ticket_id', 'time_entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
