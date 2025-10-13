<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Plan Básico',
                'description' => 'Plan ideal para empresas pequeñas con necesidades básicas de soporte.',
                'hours_included' => 5,
                'plan_cost' => 250.00,
                'extra_hour_rate' => 75.00,
                'billing_cycle' => 'monthly',
                'active' => true
            ],
            [
                'name' => 'Plan Profesional',
                'description' => 'Plan para empresas medianas con mayor volumen de soporte.',
                'hours_included' => 15,
                'plan_cost' => 650.00,
                'extra_hour_rate' => 65.00,
                'billing_cycle' => 'monthly',
                'active' => true
            ],
            [
                'name' => 'Plan Empresarial',
                'description' => 'Plan completo para empresas grandes con soporte prioritario.',
                'hours_included' => 40,
                'plan_cost' => 1500.00,
                'extra_hour_rate' => 55.00,
                'billing_cycle' => 'monthly',
                'active' => true
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
