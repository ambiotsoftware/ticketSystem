@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Asignar Plan a Cliente</h2>

    <form method="POST" action="{{ route('client-plans.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="user_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Plan</label>
            <select name="plan_id" class="form-select" required>
                <option value="">Seleccione un plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->hours_included }}h (${{ $plan->plan_cost }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de inicio</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de fin</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Costo personalizado (opcional)</label>
            <input type="number" step="0.01" name="custom_plan_cost" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Tarifa extra hora personalizada (opcional)</label>
            <input type="number" step="0.01" name="custom_extra_hour_rate" class="form-control">
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('client-plans.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
