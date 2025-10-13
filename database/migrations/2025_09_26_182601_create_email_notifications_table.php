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
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('recipient_name');
            $table->string('recipient_role'); // 'client', 'supervisor', 'hans'
            $table->string('subject');
            $table->text('body');
            $table->string('type'); // 'ticket_created', 'ticket_assigned', 'ticket_updated', etc.
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->json('metadata')->nullable(); // Para datos adicionales
            $table->timestamps();
            
            $table->index(['ticket_id', 'type']);
            $table->index(['sent', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_notifications');
    }
};
