<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientPlanRequest;
use App\Models\ClientPlan;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ClientPlanController extends Controller
{
    public function index()
    {
        $clientPlanQuery = ClientPlan::with(['user', 'plan']);

        if (request()->filled('search')) {
            $search = request()->input('search');
            $clientPlanQuery->whereHas('user', function (Builder $query) use ($search) {
                $sql = "CONCAT(first_name,' ',last_name)  like ?";
                $query->whereRaw($sql, ["%{$search}%"]);
            });
        }

        $clientPlans = $clientPlanQuery->paginate();

        return view('admin.client-plans.index', [
            'clientPlans' => $clientPlans,
        ]);
    }

    public function create()
    {
        $clientPlan = new  ClientPlan;
        $clients = User::activeClients()->get();
        $plans = Plan::where('active', 1)->get();

        return view('admin.client-plans.create', [
            'clientPlan' => $clientPlan,
            'clients' => $clients,
            'plans' => $plans
        ]);
    }

    public function store(ClientPlanRequest $request)
    {
        ClientPlan::create($request->validated());

        return redirect()->route('admin.client-plans.index')
            ->with('success', 'Plan asignado exitosamente.');
    }

    public function edit(ClientPlan $clientPlan)
    {
        $clients = User::activeClients()->get();
        $plans = Plan::where('active', 1)->get();

        return view('admin.client-plans.edit', [
            'clientPlan' => $clientPlan,
            'clients' => $clients,
            'plans' => $plans
        ]);
    }

    public function update(ClientPlanRequest $request, ClientPlan $clientPlan)
    {
        $clientPlan->update($request->validated());

        return redirect()->route('admin.client-plans.index')
            ->with('success', 'Plan actualizado con asignado exitosamente.');
    }

    public function destroy(ClientPlan $clientPlan)
    {
        $clientPlan->delete();

        return redirect()->route('admin.client-plans.index')
            ->with('success', 'Plan removido exitosamente.');
    }

    public function myPlans()
    {
        $user = auth()->user();

        $clientPlanQuery = ClientPlan::where('user_id', $user->id)
            ->with(['user', 'plan']);

        $clientPlans = $clientPlanQuery->paginate();

        return view('my-plan.index', [
            'clientPlans' => $clientPlans,
        ]);
    }
}


