@extends('layouts.ticket-system')

@section('title', 'Detalle Ticket')

@section('content')
<div class="card">
    <div style="display:flex; justify-content: space-between; align-items:center;">
        <h1 style="margin-top:0;">Ticket #{{ $ticket->id }} - {{ $ticket->titulo }}</h1>
        <div>
            @if(auth()->user()->role !== App\Enums\UserRole::TECHNICIAN)
            <a class="btn secondary" href="{{ route('tickets.edit', $ticket) }}">Editar</a>
            @endif
            <a class="btn" href="{{ route('tickets.index') }}">Volver</a>
        </div>
    </div>
    
      

    <dl class="mt-3">
        <dt><strong>Estado:</strong></dt>
        <dd><span class="tag">{{ ucfirst($ticket->estado->value) }}</span></dd>

        <dt class="mt-2"><strong>Prioridad:</strong></dt>
        <dd>{{ ucfirst($ticket->prioridad->value) }}</dd>

        <dt class="mt-2"><strong>Categoría:</strong></dt>
        <dd>{{ $ticket->category->nombre ?? '-' }}</dd>

        <dt class="mt-2"><strong>Asignado a:</strong></dt>
        <dd>{{ $ticket->assignedUser->name ?? '-' }}</dd>

        <dt class="mt-2"><strong>Cliente:</strong></dt>
        <dd>{{ $ticket->client->name ?? $ticket->client_id }} 
        @if($ticket->client->company_name)
            ({{ $ticket->client->company_name }})
        @endif
        </dd>

        <dt class="mt-2"><strong>Fecha límite:</strong></dt>
        <dd>{{ $ticket->fecha_limite?->format('Y-m-d H:i') ?? '-' }}</dd>

        <dt class="mt-2"><strong>Descripción:</strong></dt>
        <dd><pre style="white-space:pre-wrap; font-family: inherit;">{{ $ticket->descripcion }}</pre></dd>
    </dl>
    
    
    
</div>
@endsection

