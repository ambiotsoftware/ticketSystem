@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Usuarios</h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulario de búsqueda --}}
    <form method="GET" class="mb-4">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Buscar por nombre o correo..."
            class="px-4 py-2 border rounded-lg w-full md:w-1/3"
        >
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700 uppercase">ID</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700 uppercase">Nombre</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700 uppercase">Correo</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-700 uppercase">Rol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-6">{{ $user->id }}</td>
                        <td class="py-3 px-6">{{ $user->name }}</td>
                        <td class="py-3 px-6">{{ $user->email }}</td>
                        <td class="py-3 px-6">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->role_color }}">
                                {{ $user->role_label }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-3 px-6 text-center text-gray-500">No se encontraron usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $users->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection

