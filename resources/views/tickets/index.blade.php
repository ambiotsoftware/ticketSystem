@extends('layouts.ticket-system')

@section('title', 'Tickets')

@section('content')
<div class="card">
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px;">
        <h1 style="margin:0;">Tickets</h1>
        @can('create', App\Models\Ticket::class)
        <a class="btn" href="{{ route('tickets.create') }}">+ Nuevo ticket</a>
        @endcan
    </div>

    @if($tickets->count() === 0)
        <p>No hay tickets aún.</p>
    @else
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Categoría</th>
                    <th>Asignado</th>
                    @if(auth()->user()->role !== App\Enums\UserRole::CLIENT)
                        <th>Cliente</th>
                    @endif
                    <th>Creado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td><a href="{{ route('tickets.show', $t) }}">{{ $t->titulo }}</a></td>
                        <td><span class="tag {{ $t->estado->value }}">{{ ucfirst(str_replace('_', ' ', $t->estado->value)) }}</span></td>
                        <td>{{ ucfirst($t->prioridad->value) }}</td>
                        <td>{{ $t->category->nombre ?? '-' }}</td>
                        <td>{{ $t->assignedUser->name ?? '-' }}</td>
                        @if(auth()->user()->role !== App\Enums\UserRole::CLIENT)
                            <td>{{ $t->client->company_name ?? $t->client->name }}</td>
                        @endif
                        <td>{{ $t->created_at?->format('Y-m-d H:i') }}</td>
                        <td style="white-space:nowrap;">
                            @if(auth()->user()->role !== App\Enums\UserRole::TECHNICIAN)
                            <a class="btn secondary" href="{{ route('tickets.edit', $t) }}">Editar</a>
                            @endif
                            @if(auth()->user()->role === App\Enums\UserRole::ADMIN)
                            <form action="{{ route('tickets.destroy', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar ticket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
    @endif
</div>
@endsection

