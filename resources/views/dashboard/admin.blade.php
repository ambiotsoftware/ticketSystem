@extends('layouts.ticket-system')

@section('title', 'Panel de Administración')

@section('content')
<div class="card">
    <h1 style="margin-top: 0; color: #1f2937;">
        Panel de Administración - {{ auth()->user()->name }}
    </h1>
    <p style="color: #64748b;">Supervisión y gestión completa del sistema de tickets.</p>
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
        <div class="stat-number" style="color: #dc2626;">{{ $stats['unassigned_tickets'] }}</div>
        <div class="stat-label">Sin Asignar</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #2563eb;">{{ $stats['active_technicians'] }}</div>
        <div class="stat-label">Técnicos Activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #7c3aed;">{{ $stats['total_clients'] }}</div>
        <div class="stat-label">Clientes Activos</div>
    </div>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Tickets Recientes</h2>
                <div>
                    <a href="{{ route('tickets.index') }}" class="btn secondary">Ver Todos</a>
                </div>
            </div>

            @if($recent_tickets->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Cliente</th>
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
                                    {{ Str::limit($ticket->titulo, 30) }}
                                </a>
                            </td>
                            <td>{{ $ticket->client->company_name ?? $ticket->client->name }}</td>
                            <td><span class="tag {{ $ticket->estado->value }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado->value)) }}</span></td>
                            <td>
                                <span class="priority-{{ $ticket->prioridad === App\Enums\TicketPriority::HIGH || $ticket->prioridad === App\Enums\TicketPriority::CRITICAL ? 'high' : ($ticket->prioridad === App\Enums\TicketPriority::MEDIUM ? 'medium' : 'low') }}">
                                    {{ ucfirst($ticket->prioridad->value) }}
                                </span>
                            </td>
                            <td>{{ $ticket->assignedUser->name ?? 'Sin asignar' }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn small secondary">Ver</a>
                                <button class="btn small" onclick="openAssignModal({{ $ticket->id }})">Asignar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center" style="padding: 40px;">
                    <p style="color: #64748b;">No hay tickets recientes en el sistema.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <h3 style="margin-top: 0;">Acciones de Administración</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('tickets.index') }}" class="btn secondary">📋 Gestionar Tickets</a>
                <a href="{{ route('categories.index') }}" class="btn secondary">🏷️ Categorías</a>
                <a href="{{ route('profile.edit') }}" class="btn secondary">👤 Mi Perfil</a>
                <a href="{{ route('users.create') }}" class="btn secondary">👥 Registrar Usuario</a>
                <a href="https://helpdesk.networksmayan.com/users" class="btn secondary" target="_blank">🔍 Consultar Usuarios</a>
                <a href="{{ route('client.plan') }}" class="btn btn-secondary w-100 mb-2">📦 Consultar Planes</a>


            </div>
        </div>

        @if($technician_workload->count() > 0)
        <div class="card">
            <h3 style="margin-top: 0;">Carga de Trabajo</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($technician_workload as $tech)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                        <span style="font-weight: 500;">{{ $tech->name }}</span>
                        <span class="tag {{ $tech->active_tickets > 5 ? 'danger' : ($tech->active_tickets > 2 ? 'warning' : 'success') }}">
                            {{ $tech->active_tickets }} tickets
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="card">
            <h3 style="margin-top: 0;">Estadísticas del Sistema</h3>
            <dl style="margin: 0;">
                <dt style="font-weight: 500; color: #374151;">Rendimiento General:</dt>
                <dd style="margin-left: 0; margin-bottom: 8px; color: #6b7280;">
                    {{ round(($stats['total_tickets'] - $stats['open_tickets']) / max($stats['total_tickets'], 1) * 100) }}% tickets resueltos
                </dd>

                <dt style="font-weight: 500; color: #374151;">Tickets sin asignar:</dt>
                <dd style="margin-left: 0; margin-bottom: 8px; color: {{ $stats['unassigned_tickets'] > 0 ? '#dc2626' : '#16a34a' }};">
                    {{ $stats['unassigned_tickets'] }} pendientes
                </dd>

                <dt style="font-weight: 500; color: #374151;">Técnicos disponibles:</dt>
                <dd style="margin-left: 0; color: #6b7280;">
                    {{ $stats['active_technicians'] }} activos
                </dd>
            </dl>
        </div>
    </div>
</div>

@if($stats['unassigned_tickets'] > 0)
<div class="card" style="border-left: 4px solid #dc2626;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0; color: #dc2626;">⚠️ Atención Requerida</h3>
            <p style="margin: 8px 0 0 0; color: #6b7280;">
                Hay {{ $stats['unassigned_tickets'] }} ticket(s) sin asignar que requieren atención inmediata.
            </p>
        </div>
        <a href="{{ route('tickets.index') }}?filter=unassigned" class="btn">Revisar Tickets</a>
    </div>
</div>
@endif
<!-- Modal Asignar Técnico -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Asignar Técnico al Ticket</h3>
        </div>
        <form id="assignForm" method="POST" action="{{ route('tickets.assign', ['ticket' => 0]) }}">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="ticket_id" id="assign_ticket_id" value="">
                <label for="assign_technician">Seleccionar técnico:</label>
                <select id="assign_technician" name="assigned_user_id" class="input" required>
                    <option value="">-- Selecciona un técnico --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">{{ $tech->name }} ({{ $tech->email }})</option>
                    @endforeach
                </select>
                <small style="color:#64748b;">El ticket cambiará a estado "En seguimiento".</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn secondary" onclick="closeModal('assignModal')">Cancelar</button>
                <button type="submit" class="btn">Asignar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModal(ticketId) {
    document.getElementById('assign_ticket_id').value = ticketId;
    // ajustar acción al ticket específico
    const form = document.getElementById('assignForm');
    form.action = "{{ url('/tickets') }}/" + ticketId + "/assign";
    openModal('assignModal');
}
</script>

@endsection
