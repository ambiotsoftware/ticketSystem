@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        <i class="fas fa-user-cog"></i> Administrar Usuarios
    </h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow-md">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-200 uppercase text-gray-600">
                <tr>
                    <th class="py-3 px-4 text-left">Nombre</th>
                    <th class="py-3 px-4 text-left">Correo</th>
                    <th class="py-3 px-4 text-left">Rol</th>
                    <th class="py-3 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        $roleValue = $user->role instanceof \App\Enums\UserRole ? $user->role->value : $user->role;
                        [$color, $label] = match($roleValue) {
                            'admin' => ['text-red-700 font-semibold', 'Administrador'],
                            'client' => ['text-green-700 font-semibold', 'Cliente'],
                            'technician' => ['text-purple-700 font-semibold', 'Técnico'],
                            default => ['text-gray-700', 'Desconocido'],
                        };
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4 {{ $color }}">{{ ucfirst($label) }}</td>
                        <td class="py-3 px-4 flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded flex items-center gap-1 transition">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded flex items-center gap-1 transition">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Font Awesome --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
