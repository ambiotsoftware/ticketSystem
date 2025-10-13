<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Plan;
use App\Models\ClientPlan;
use Illuminate\Http\Request;

class ClientPlanController extends Controller
{
    // Mostrar formulario para asignar planes a un cliente
    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);
        $plans = Plan::where('active', 1)->get(); // solo planes activos
        $assignedPlans = $client->plans->pluck('id')->toArray();

        return view('client_plans.edit', compact('client', 'plans', 'assignedPlans'));
    }

    // Listado general de clientes con planes
    public function index()
    {
        $clients = Client::with('plans')->get();
        return view('client_plans.index', compact('clients'));
    }

    // Guardar planes asignados al cliente
    public function update(Request $request, $clientId)
    {
        $client = Client::findOrFail($clientId);

        $request->validate([
            'plans' => 'array', // puede ser vacío si desasignas todos
            'plans.*' => 'exists:plans,id'
        ]);

        $client->plans()->sync($request->plans ?? []);

        return redirect()->route('clients.index')->with('success', 'Planes asignados correctamente');
    }

    // Mostrar planes asignados según rol
    public function myPlan()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus planes.');
        }

        $role = $user->role->value ?? null;

        // Cliente
        if ($role === 'client') {
            $plans = $user->clientPlans()->with('plan')->get();

            if ($plans->isEmpty()) {
                return view('client_plans.my_plan', [
                    'plans' => [],
                    'message' => 'Aún no tienes ningún plan asignado.'
                ]);
            }

            return view('client_plans.my_plan', compact('plans'));
        }

        // Admin
        if ($role === 'admin') {
            $plans = ClientPlan::with(['user', 'plan'])->get();
            return view('client_plans.my_plan_admin', compact('plans'));
        }

        // Otros roles
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    // Solo Admin: vista de planes de todos los clientes
    public function myPlanAdmin()
    {
        $user = auth()->user();

        if (!$user || $user->role->value !== 'admin') {
            abort(403, 'Solo los administradores pueden acceder a esta sección.');
        }

        $plans = ClientPlan::with(['user', 'plan'])->get();
        return view('client_plans.my_plan_admin', compact('plans'));
    }
}


