@extends('layouts.ticket-system')

@section('title', 'Editar Ticket')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Editar Ticket #{{ $ticket->id }}</h1>

    <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="mt-3">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-6">
                <label>Título</label>
                <input type="text" name="titulo" class="input" value="{{ old('titulo', $ticket->titulo) }}" required>
            </div>
            @if(auth()->user()->role !== App\Enums\UserRole::CLIENT)
            <div class="col-6">
                <label>Estado</label>
                <select name="estado" class="input" required>
                    @foreach(App\Enums\TicketStatus::cases() as $e)
                        <option value="{{ $e->value }}" @selected(old('estado', $ticket->estado->value)===$e->value)>{{ ucfirst(str_replace('_',' ', $e->value)) }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-12">
                <label>Descripción</label>
                <textarea name="descripcion" rows="6" class="input" required>{{ old('descripcion', $ticket->descripcion) }}</textarea>
            </div>
            <div class="col-6">
                <label>Prioridad</label>
                <select name="prioridad" class="input" required>
                    @foreach(App\Enums\TicketPriority::cases() as $p)
                        <option value="{{ $p->value }}" @selected(old('prioridad', $ticket->prioridad->value)===$p->value)>{{ ucfirst($p->value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label>Categoría</label>
                <select name="category_id" class="input">
                    <option value="">-- Sin categoría --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $ticket->category_id)==$c->id)>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role !== App\Enums\UserRole::CLIENT)
            <div class="col-6">
                <label>Asignado a</label>
                <select name="assigned_user_id" class="input">
                    <option value="">-- Sin asignar --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(old('assigned_user_id', $ticket->assigned_user_id)==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-6">
                <label>Fecha límite</label>
                <input type="datetime-local" name="fecha_limite" class="input" value="{{ old('fecha_limite', optional($ticket->fecha_limite)->format('Y-m-d\TH:i')) }}">
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('tickets.show', $ticket) }}" class="btn secondary">Cancelar</a>
            <button type="submit" class="btn">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection

