@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Gestión de asignación de planes</h2>

        {{-- 🧍 Botón para crear plan --}}
        <a href="{{ route('admin.client-plans.create') }}" class="btn btn-primary mb-3">
            + Nueva Asignación
        </a>

        {{-- 🔍 Buscador y filtro por rol (SIN estado) --}}
        <form method="GET" action="{{ route('admin.client-plans.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                {{-- Buscador --}}
                <div class="col-md-6">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="Nombre..." value="{{ request('search') }}">
                </div>

                {{-- Filtro  --}}
                <div class="col-md-4">

                </div>

                {{-- Botones --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-50">Filtrar</button>
                    <a href="{{ route('admin.client-plans.index') }}" class="btn btn-secondary w-50">Limpiar</a>
                </div>
            </div>
        </form>

        {{-- 🧾 Tabla de usuarios --}}
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5em" class="text-center">ID</th>
                    <th style="width: 15em" class="">Usuario</th>
                    <th style="width: 15em" class="">Plan</th>
                    <th style="width: 10em" class="text-center">Estado</th>
                    <th style="width: 10em" class="text-center">Fecha inicio</th>
                    <th style="width: 10em" class="text-center">Fecha Fin</th>
                    <th style="width: 15em" class="text-center">Costo plan p.</th>
                    <th style="width: 15em" class="text-center">Horas extra p.</th>
                    <th style="width: 15em" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($clientPlans as $clientPlan)
                <tr>
                    <td class="text-center align-middle">{{ $clientPlan->id }}</td>
                    <td class="align-middle">{{ $clientPlan->user->name }}</td>
                    <td class="align-middle">{{ $clientPlan?->plan?->name }}</td>
                    <td class="text-center align-middle">
                        @if($clientPlan->active == 1)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">{{ $clientPlan->start_date?->format('d/m/Y') }}</td>
                    <td class="text-center align-middle">{{ $clientPlan->end_date?->format('d/m/Y') }}</td>
                    <td class="text-center align-middle">{{ $clientPlan->custom_plan_cost ?? '--' }}</td>
                    <td class="text-center align-middle">{{ $clientPlan->custom_extra_hour_rate ?? '--' }}</td>


                    <td class="text-center align-middle">
                        <a href="{{ route('admin.client-plans.edit', $clientPlan) }}" class="btn btn-warning btn-sm">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('admin.client-plans.destroy', $clientPlan) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este usuario?')" class="btn btn-danger btn-sm">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-3">No se encontraron usuarios</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $clientPlans->links() }}
        </div>
    </div>
@endsection
