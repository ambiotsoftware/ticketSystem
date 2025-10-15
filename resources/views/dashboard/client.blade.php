@extends('layouts.ticket-system')

@section('title', 'Panel del Cliente')

@section('content')
<div class="card">
    <h1 style="margin-top: 0; color: #1f2937;">
        Bienvenido, {{ auth()->user()->first_name ?? auth()->user()->name }}
        @if(auth()->user()->company_name)
            <span style="color: #64748b; font-weight: normal;"> - {{ auth()->user()->company_name }}</span>
        @endif
    </h1>
    <p style="color: #64748b;">Gestiona tus solicitudes de soporte técnico desde este panel.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_tickets'] }}</div>
        <div class="stat-label">Total de Tickets</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #16a34a;">{{ $stats['open_tickets'] }}</div>
        <div class="stat-label">Tickets Abiertos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #2563eb;">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">En Seguimiento</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #64748b;">{{ $stats['closed_tickets'] }}</div>
        <div class="stat-label">Tickets Cerrados</div>
    </div>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Tickets Recientes</h2>
                <a href="{{ route('tickets.create') }}" class="btn">+ Nuevo Ticket</a>
            </div>

            @if($recent_tickets->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Asignado a</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" style="color: #2563eb; text-decoration: none;">
                                    {{ Str::limit($ticket->titulo, 40) }}
                                </a>
                            </td>
                            <td><span class="tag {{ $ticket->estado->value }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado->value)) }}</span></td>
                            <td>
                                <span class="priority-{{ $ticket->prioridad === App\Enums\TicketPriority::HIGH || $ticket->prioridad === App\Enums\TicketPriority::CRITICAL ? 'high' : ($ticket->prioridad === App\Enums\TicketPriority::MEDIUM ? 'medium' : 'low') }}">
                                    {{ ucfirst($ticket->prioridad->value) }}
                                </span>
                            </td>
                            <td>{{ $ticket->assignedUser->name ?? '-' }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn small secondary">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('tickets.index') }}" class="btn secondary">Ver Todos los Tickets</a>
                </div>
            @else
                <div class="text-center" style="padding: 40px;">
                    <p style="color: #64748b; margin-bottom: 16px;">No tienes tickets aún</p>
                    <a href="{{ route('tickets.create') }}" class="btn">Crear tu Primer Ticket</a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <h3 style="margin-top: 0;">Acciones Rápidas</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('tickets.create') }}" class="btn">📝 Crear Ticket</a>
                <a href="{{ route('tickets.index') }}" class="btn secondary">📋 Estado del Ticket</a>
                <a href="{{ route('my-plans.index') }}" class="btn secondary">📊 Mi Plan/Servicio Contratado</a>
                <a href="{{ route('profile.edit') }}" class="btn secondary">👤 Mi Perfil</a>
            </div>
        </div>

        @if(auth()->user()->logo_path)
            <div class="card">
                <h3 style="margin-top: 0;">Logo de tu Empresa</h3>
                <div class="text-center">
                    <img src="{{ asset('storage/' . auth()->user()->logo_path) }}" alt="Logo de {{ auth()->user()->company_name }}" style="max-width: 100%; max-height: 120px; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
