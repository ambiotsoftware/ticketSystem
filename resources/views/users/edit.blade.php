<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-xl rounded-2xl p-10 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            Editar Usuario
        </h2>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de edición --}}
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="first_name" class="block text-gray-700 font-medium mb-1">Nombre</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="last_name" class="block text-gray-700 font-medium mb-1">Apellido</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Correo</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Nueva Contraseña (opcional)</label>
                <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" placeholder="Déjalo vacío para mantener la actual">
            </div>

            <div>
                <label for="role" class="block text-gray-700 font-medium mb-1">Rol</label>
                <select name="role" id="role" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
                    <option value="">Selecciona un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role', $user->role->value ?? '') == $role->value ? 'selected' : '' }}>
                            @switch($role->value)
                                @case('admin') Administrador @break
                                @case('user') Cliente @break
                                @ca
