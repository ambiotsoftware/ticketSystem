@extends('layouts.ticket-system')

@section('title', 'Categoría: ' . $category->nombre)

@section('content')
<div class="card">
    <div style="display:flex; justify-content: space-between; align-items:center;">
        <h1 style="margin-top:0;">
            <span class="tag" style="background: {{ $category->color }}; color: #fff;">{{ $category->nombre }}</span>
        </h1>
        <div>
            <a class="btn secondary" href="{{ route('categories.edit', $category) }}">Editar</a>
            <a class="btn" href="{{ route('categories.index') }}">Volver</a>
        </div>
    </div>

    <dl class="mt-3">
        <dt><strong>Descripción:</strong></dt>
        <dd>{{ $category->descripcion ?: '-' }}</dd>

        <dt class="mt-2"><strong>Estado:</strong></dt>
        <dd>{{ $category->activa ? 'Activa' : 'Inactiva' }}</dd>

        <dt class="mt-2"><strong>Total de tickets:</strong></dt>
        <dd>{{ $category->tickets_count }}</dd>
    </dl>
</div>

@if($tickets->count() > 0)
<div class="card mt-4">
    <h2 style="margin-top:0;">Tickets en esta categoría</h2>
    
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Asignado</th>
                    <th>Creado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td><a href="{{ route('tickets.show', $t) }}">{{ $t->titulo }}</a></td>
                        <td><span class="tag">{{ $t->estado }}</span></td>
                        <td>{{ ucfirst($t->prioridad) }}</td>
                        <td>{{ $t->assignedUser->name ?? '-' }}</td>
                        <td>{{ $t->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <a class="btn secondary" href="{{ route('tickets.show', $t) }}">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>
@endif
@endsection
