@extends('layouts.ticket-system')

@section('title', 'Notificaciones de Correo')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin: 0;">Notificaciones de Correo</h1>
        <div>
            <span class="tag">
                {{ $notifications->where('sent', false)->count() }} pendientes
            </span>
            <span class="tag success">
                {{ $notifications->where('sent', true)->count() }} enviadas
            </span>
        </div>
    </div>
    
    <p style="color: #64748b; margin-bottom: 20px;">
        Gestiona las notificaciones generadas automáticamente por el sistema. 
        Las notificaciones se crean cuando se registran tickets o se asignan técnicos.
    </p>

    @if($notifications->count() > 0)
        <form method="POST" action="{{ route('admin.notifications.mark-sent') }}">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <button type="button" onclick="toggleAll()" class="btn secondary">
                    Seleccionar/Deseleccionar Todo
                </button>
                <button type="submit" class="btn success" onclick="return confirm('¿Marcar las notificaciones seleccionadas como enviadas?')">
                    📧 Marcar como Enviadas
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Estado</th>
                            <th>Ticket</th>
                            <th>Para</th>
                            <th>Asunto</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Enviado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr>
                            <td>
                                @if(!$notification->sent)
                                    <input type="checkbox" name="notification_ids[]" value="{{ $notification->id }}" class="notification-checkbox">
                                @endif
                            </td>
                            <td>
                                @if($notification->sent)
                                    <span class="tag success">✅ Enviado</span>
                                @else
                                    <span class="tag warning">⏳ Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tickets.show', $notification->ticket) }}" style="color: #2563eb;">
                                    #{{ $notification->ticket->id }}
                                </a>
                                <br>
                                <small style="color: #64748b;">{{ $notification->ticket->client->company_name ?? $notification->ticket->client->name }}</small>
                            </td>
                            <td>
                                <strong>{{ $notification->recipient_name }}</strong>
                                <br>
                                <small style="color: #64748b;">{{ $notification->recipient_email }}</small>
                                <br>
                                <span class="tag {{ 
                                    $notification->recipient_role === 'hans' ? 'danger' : 
                                    ($notification->recipient_role === 'client' ? 'success' : 'secondary') 
                                }}">
                                    {{ ucfirst($notification->recipient_role) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($notification->subject, 40) }}</td>
                            <td>
                                <span class="tag secondary">{{ $notification->type }}</span>
                            </td>
                            <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($notification->sent)
                                    {{ $notification->sent_at->format('d/m/Y H:i') }}
                                @else
                                    <span style="color: #f59e0b;">Sin enviar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.notifications.show', $notification) }}" class="btn small secondary">
                                    👁️ Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center" style="padding: 40px;">
            <p style="color: #64748b; margin-bottom: 16px;">No hay notificaciones aún</p>
            <p style="color: #64748b; font-size: 14px;">
                Las notificaciones se generarán automáticamente cuando se registren tickets.
            </p>
        </div>
    @endif
</div>

<div class="card">
    <h3 style="margin-top: 0;">ℹ️ Información</h3>
    <p style="color: #64748b; margin: 0; line-height: 1.6;">
        <strong>Funcionamiento:</strong> El sistema genera notificaciones automáticamente cuando se crean tickets.
        Se envían a: <strong>cliente (confirmación)</strong>, <strong>Hans Higueros (notificación)</strong> y 
        <strong>supervisores/admins (para asignación)</strong>. 
        <br><br>
        En un entorno de producción, estas notificaciones se procesarían con un sistema de colas 
        y se enviarían como correos electrónicos reales.
    </p>
</div>

<script>
function toggleAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

document.getElementById('selectAll').addEventListener('change', toggleAll);
</script>
@endsection
