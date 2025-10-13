<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'hours_included' => 'required|integer',
            'plan_cost' => 'required|numeric',
            'extra_hour_rate' => 'required|numeric',
            'billing_cycle' => 'required',
            'active' => 'boolean'
        ]);

        Plan::create($validated);
        return redirect()->route('plans.index')->with('success', 'Plan creado exitosamente.');
    }

    public function show(Plan $plan)
    {
        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'hours_included' => 'required|integer',
            'plan_cost' => 'required|numeric',
            'extra_hour_rate' => 'required|numeric',
            'billing_cycle' => 'required',
            'active' => 'boolean'
        ]);

        $plan->update($validated);
        return redirect()->route('plans.index')->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan eliminado correctamente.');
    }
}

