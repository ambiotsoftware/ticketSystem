@extends('layouts.ticket-system')

@section('title', 'Panel del Técnico')

@section('content')
<div class="card">
    <h1 style="margin-top: 0; color: #1f2937;">
        Panel de Técnico - {{ auth()->user()->first_name ?? auth()->user()->name }}
    </h1>
    <p style="color: #64748b;">Gestiona tus tickets asignados y controla el tiempo de trabajo.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['assigned_tickets'] }}</div>
        <div class="stat-label">Tickets Asignados</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #2563eb;">{{ $stats['active_tickets'] }}</div>
        <div class="stat-label">Activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #f59e0b;">{{ $stats['paused_tickets'] }}</div>
        <div class="stat-label">Pausados</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #16a34a;">{{ $stats['completed_today'] }}</div>
        <div class="stat-label">Finalizados Hoy</div>
    </div>
</div>

@if($assigned_tickets->count() > 0)
<div class="card">
    <h2 style="margin-top: 0;">Mis Tickets Asignados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha Creación</th>
                <th>Controles de Tiempo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assigned_tickets as $ticket)
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
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($ticket->estado === App\Enums\TicketStatus::OPEN || ($ticket->estado === App\Enums\TicketStatus::TRACKING && !$ticket->has_active_time_entry))
                        <button class="btn success small" onclick="startTimer({{ $ticket->id }})">
                            ▶️ Iniciar
                        </button>
                    @elseif($ticket->estado === App\Enums\TicketStatus::TRACKING && $ticket->has_active_time_entry)
                        <button class="btn warning small" onclick="pauseTimer({{ $ticket->id }})">
                            ⏸️ Pausar
                        </button>
                        <button class="btn danger small" onclick="stopTimer({{ $ticket->id }})">
                            ⏹️ Finalizar
                        </button>
                    @elseif($ticket->estado === App\Enums\TicketStatus::PAUSED)
                        <button class="btn success small" onclick="resumeTimer({{ $ticket->id }})">
                            ▶️ Reanudar
                        </button>
                        <button class="btn danger small" onclick="stopTimer({{ $ticket->id }})">
                            ⏹️ Finalizar
                        </button>
                    @endif
                </td>
                <td>
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn small secondary">Ver</a>
                    @if($ticket->estado !== App\Enums\TicketStatus::CLOSED)
                        <button class="btn small warning" onclick="reassignTicket({{ $ticket->id }})">
                            🔄 Reasignar
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        <a href="{{ route('tickets.index') }}" class="btn secondary">Ver Todos los Tickets</a>
    </div>
</div>
@else
<div class="card text-center" style="padding: 40px;">
    <h3 style="color: #64748b;">No tienes tickets asignados</h3>
    <p style="color: #64748b;">Los tickets te serán asignados automáticamente o por un administrador.</p>
</div>
@endif


<!-- Modal para comentarios de tiempo -->
<div id="timeCommentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Agregar Comentario</h3>
        </div>
        <form id="timeCommentForm">
            @csrf
            <div class="modal-body">
                <label>Comentario (requerido):</label>
                <textarea name="comment" rows="4" class="input" placeholder="Describe el trabajo realizado, razón de la pausa, o resolución del problema..." required></textarea>
                
                <div style="margin-top: 16px;">
                    <label>Adjuntar capturas de pantalla (opcional):</label>
                    <input type="file" name="attachments[]" class="input" multiple accept="image/*,.pdf,.doc,.docx">
                    <small style="color: #64748b;">Formatos permitidos: imágenes, PDF, Word. Máximo 5MB por archivo.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn secondary" onclick="closeModal('timeCommentModal')">Cancelar</button>
                <button type="submit" class="btn">Confirmar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para reasignación -->
<div id="reassignModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reasignar Ticket</h3>
        </div>
        <form id="reassignForm">
            @csrf
            <div class="modal-body">
                <label>Razón de la reasignación:</label>
                <textarea name="reassign_reason" rows="3" class="input" placeholder="Explica por qué no puedes atender este ticket..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn secondary" onclick="closeModal('reassignModal')">Cancelar</button>
                <button type="submit" class="btn warning">Reasignar</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentTicketId = null;
let currentAction = null;

function startTimer(ticketId) {
    currentTicketId = ticketId;
    currentAction = 'start';
    document.getElementById('modalTitle').textContent = 'Iniciar Trabajo en Ticket';
    document.querySelector('#timeCommentForm textarea[name="comment"]').placeholder = 'Describe cómo vas a abordar este ticket...';
    openModal('timeCommentModal');
}

function pauseTimer(ticketId) {
    currentTicketId = ticketId;
    currentAction = 'pause';
    document.getElementById('modalTitle').textContent = 'Pausar Trabajo';
    document.querySelector('#timeCommentForm textarea[name="comment"]').placeholder = 'Explica la razón de la pausa y el progreso actual...';
    openModal('timeCommentModal');
}

function stopTimer(ticketId) {
    currentTicketId = ticketId;
    currentAction = 'stop';
    document.getElementById('modalTitle').textContent = 'Finalizar Ticket';
    document.querySelector('#timeCommentForm textarea[name="comment"]').placeholder = 'Describe la resolución del problema y el trabajo completado...';
    openModal('timeCommentModal');
}

function resumeTimer(ticketId) {
    // Reanudar es igual que iniciar
    startTimer(ticketId);
    currentAction = 'resume';
}

function reassignTicket(ticketId) {
    currentTicketId = ticketId;
    openModal('reassignModal');
}


// Manejar envío del formulario de comentarios de tiempo
document.getElementById('timeCommentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', currentAction);
    
    fetch(`/tickets/${currentTicketId}/time-control`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json' // Asegurarse de que Laravel devuelva JSON
        },
        body: formData
    })
    .then(async response => {
        if (response.ok) {
            return response.json();
        }
        // Si la respuesta no es OK, procesar el error
        const errorData = await response.json();
        let errorMessage = 'Error al procesar la acción.';
        if (errorData.errors) {
            // Errores de validación de Laravel
            errorMessage = Object.values(errorData.errors).map(e => e.join('\n')).join('\n');
        } else if (errorData.message) {
            // Otros errores JSON
            errorMessage = errorData.message;
        }
        throw new Error(errorMessage);
    })
    .then(data => {
        closeModal('timeCommentModal');
        location.reload();
    })
    .catch(error => {
        alert(error.message);
    });
});

// Manejar envío del formulario de reasignación
document.getElementById('reassignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/tickets/${currentTicketId}/reassign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    }).then(response => {
        if (response.ok) {
            closeModal('reassignModal');
            location.reload();
        } else {
            alert('Error al reasignar el ticket');
        }
    });
});
</script>
@endsection
