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
        Schema::create('client_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente
            $table->foreignId('plan_id')->constrained()->onDelete('cascade'); // Plan contratado
            $table->date('start_date'); // Fecha de inicio del periodo
            $table->date('end_date'); // Fecha de fin del periodo
            $table->boolean('active')->default(true);
            $table->decimal('custom_plan_cost', 10, 2)->nullable(); // Costo personalizado si difiere del plan
            $table->decimal('custom_extra_hour_rate', 8, 2)->nullable(); // Tarifa personalizada
            $table->timestamps();
            
            $table->index(['user_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_plans');
    }
};
