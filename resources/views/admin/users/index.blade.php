@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Gestión de Usuarios</h2>

    {{-- 🧍 Botón para crear usuario --}}
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
        👥 Nuevo Usuario
    </a>

    {{-- ✅ Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Buscador y filtro por rol (SIN estado) --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            {{-- Buscador --}}
            <div class="col-md-6">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Nombre, apellido o correo..." value="{{ request('search') }}">
            </div>

            {{-- Filtro por rol --}}
            <div class="col-md-4">
                <label for="role" class="form-label">Rol</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Todos</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="technician" {{ request('role') == 'technician' ? 'selected' : '' }}>Técnico</option>
                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success w-50">Filtrar</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary w-50">Limpiar</a>
            </div>
        </div>
    </form>

    {{-- 🧾 Tabla de usuarios --}}
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    // Si el rol es Enum, obtener su valor
                    $roleValue = is_object($user->role) ? $user->role->value : $user->role;
                @endphp
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($roleValue)
                            @case('admin')
                                Administrador
                                @break
                            @case('technician')
                                Técnico
                                @break
                            @case('client')
                                Cliente
                                @break
                            @default
                                {{ ucfirst($roleValue) }}
                        @endswitch
                    </td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este usuario?')" class="btn btn-danger btn-sm">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">No se encontraron usuarios</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔢 Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection


