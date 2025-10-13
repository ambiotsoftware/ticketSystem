@extends('layouts.ticket-system')

@section('title', 'Nuevo Ticket')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Nuevo Ticket</h1>

    <form method="POST" action="{{ route('tickets.store') }}" class="mt-3">
        @csrf
        <div class="row">
            <div class="col-6">
                <label>Título</label>
                <input type="text" name="titulo" class="input" value="{{ old('titulo') }}" required>
            </div>
            <div class="col-6">
                <label>Prioridad</label>
                <select name="prioridad" class="input" required>
                    @foreach(App\Enums\TicketPriority::cases() as $p)
                        <option value="{{ $p->value }}" @selected(old('prioridad', App\Enums\TicketPriority::MEDIUM->value) === $p->value)>{{ ucfirst($p->value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label>Descripción</label>
                <textarea name="descripcion" rows="6" class="input" required>{{ old('descripcion') }}</textarea>
            </div>
            <div class="col-6">
                <label>Categoría</label>
                <select name="category_id" class="input">
                    <option value="">-- Sin categoría --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role !== App\Enums\UserRole::CLIENT)
                <div class="col-6">
                    <label>Asignar a (Opcional)</label>
                    <select name="assigned_user_id" class="input">
                        <option value="">-- Sin asignar (se asignará después) --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('assigned_user_id')==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                    <small style="color: #666; font-size: 0.9rem;">Si no se asigna ahora, deberá asignarse desde el panel de administración.</small>
                </div>
            @endif
            <div class="col-6">
                <label>Fecha límite</label>
                <input type="datetime-local" name="fecha_limite" class="input" value="{{ old('fecha_limite') }}">
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('tickets.index') }}" class="btn secondary">Cancelar</a>
            <button type="submit" class="btn">Crear Ticket</button>
        </div>
    </form>
</div>
@endsection

