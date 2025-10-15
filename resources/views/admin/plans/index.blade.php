@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Gestión de Planes</h2>

        {{-- 🧍 Botón para crear plan --}}
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary mb-3">
            + Nuevo Plan
        </a>

        {{-- 🔍 Buscador y filtro por rol (SIN estado) --}}
        <form method="GET" action="{{ route('admin.plans.index') }}" class="mb-4">
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
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary w-50">Limpiar</a>
                </div>
            </div>
        </form>

        {{-- 🧾 Tabla de usuarios --}}
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5em" class="text-center">ID</th>
                    <th style="width: 15em">Nombre</th>
                    <th style="width: 20em">Descripción</th>
                    <th style="width: 10em" class="text-center">Horas</th>
                    <th style="width: 10em" class="text-center">Costo</th>
                    <th style="width: 10em" class="text-center">H. extras</th>
                    <th style="width: 10em" class="text-center">Período</th>
                    <th style="width: 10em" class="text-center">Estado</th>
                    <th style="width: 10em" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td class="text-center align-middle">{{ $plan->id }}</td>
                        <td class="align-middle">{{ $plan->name }}</td>
                        <td class="align-middle">{{ $plan->description }}</td>
                        <td class="text-center align-middle">{{ $plan->hours_included }}</td>
                        <td class="text-center align-middle">{{ $plan->plan_cost }}</td>
                        <td class="text-center align-middle">{{ $plan->extra_hour_rate }}</td>
                        <td class="text-center align-middle">{{ $plan->billing_cycle?->trans() }}</td>
                        <td class="text-center align-middle">
                            @if($plan->active == 1)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-warning btn-sm">
                                ✏️ Editar
                            </a>
                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="d-inline">
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
            {{ $plans->links() }}
        </div>
    </div>
@endsection
