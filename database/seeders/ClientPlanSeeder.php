<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientPlan;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class ClientPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el cliente de prueba y el plan profesional
        $client = User::where('role', 'client')->first();
        $plan = Plan::where('name', 'Plan Profesional')->first();
        
        if ($client && $plan) {
            ClientPlan::create([
                'user_id' => $client->id,
                'plan_id' => $plan->id,
                'start_date' => Carbon::now()->startOfMonth(), // Inicio del mes actual
                'end_date' => Carbon::now()->endOfMonth(), // Final del mes actual
                'active' => true
            ]);
        }
    }
}
