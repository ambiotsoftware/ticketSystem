<?php

namespace App\Http\Controllers;

use App\Enums\BillingCycleEnum;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $queryPlan  = Plan::query();

        if (request()->filled('search')) {
            $search = request()->input('search');
            $queryPlan->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $plans = $queryPlan->paginate();

        return view('admin.plans.index', [
            'plans' => $plans
        ]);
    }

    public function create()
    {
        $plan = new Plan;

        $billingCycle = BillingCycleEnum::pluck();

        return view('admin.plans.create', [
            'plan' => $plan,
            'billingCycle' => $billingCycle
        ]);
    }

    public function store(PlanRequest $request)
    {
        Plan::create($request->validated());

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan creado exitosamente.');
    }

    public function show(Plan $plan)
    {
        return view('admin.plans.show', [
            'plan' => $plan
        ]);
    }

    public function edit(Plan $plan)
    {
        $billingCycle = BillingCycleEnum::pluck();

        return view('admin.plans.edit', [
            'plan' => $plan,
            'billingCycle' => $billingCycle
        ]);
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan eliminado correctamente.');
    }
}

