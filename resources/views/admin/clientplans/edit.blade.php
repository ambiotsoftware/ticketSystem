@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Plan Asignado</h2>

    <form method="POST" action="{{ route('client-plans.update', $clientPlan) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $clientPlan->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Plan</label>
            <select name="plan_id" class="form-select" required>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ $clientPlan->plan_id == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Inicio</label>
            <input type="date" name="start_date" value="{{ $clientPlan->start_date->format('Y-m-d') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fin</label>
            <input type="date" name="end_date" value="{{ $clientPlan->end_date->format('Y-m-d') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Activo</label>
            <select name="active" class="form-select">
                <option value="1" {{ $clientPlan->active ? 'selected' : '' }}>S¨ª</option>
                <option value="0" {{ !$clientPlan->active ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Costo personalizado</label>
            <input type="number" step="0.01" name="custom_plan_cost" class="form-control"
                value="{{ $clientPlan->custom_plan_cost }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tarifa extra hora personalizada</label>
            <input type="number" step="0.01" name="custom_extra_hour_rate" class="form-control"
                value="{{ $clientPlan->custom_extra_hour_rate }}">
        </div>

        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('client-plans.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection

