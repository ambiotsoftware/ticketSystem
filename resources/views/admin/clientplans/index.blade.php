@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Planes Asignados a Clientes</h2>
    <a href="{{ route('client-plans.create') }}" class="btn btn-primary mb-3">Asignar Nuevo Plan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Plan</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientPlans as $cp)
            <tr>
                <td>{{ $cp->user->name }}</td>
                <td>{{ $cp->plan->name }}</td>
                <td>{{ $cp->start_date->format('Y-m-d') }}</td>
                <td>{{ $cp->end_date->format('Y-m-d') }}</td>
                <td>{{ $cp->active ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('client-plans.edit', $cp) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('client-plans.destroy', $cp) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta asignación?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $clientPlans->links() }}
</div>
@endsection
