<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-10 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center justify-center">
            Crear Usuario
        </h2>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Mensaje de errores -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="first_name">Nombre</label>
                <input type="text" name="first_name" id="first_name"
                       value="{{ old('first_name') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Ingrese el nombre" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="last_name">Apellido</label>
                <input type="text" name="last_name" id="last_name"
                       value="{{ old('last_name') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Ingrese el apellido" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="email">Correo electrónico</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Ingrese el correo electrónico" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="password">Contraseña</label>
                <input type="password" name="password" id="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Ingrese la contraseña" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="role">Rol</label>
                @php
                    // Normaliza los roles a un formato seguro
                    $normalizedRoles = [];

                    if (isset($roles)) {
                        foreach ($roles as $key => $role) {
                            if (is_object($role) && property_exists($role, 'value')) {
                                // Si viene desde un enum
                                $value = $role->value;
                                $label = match($value) {
                                    'admin'   => 'Administrador',
                                    'support' => 'Soporte',
                                    'client'  => 'Cliente',
                                    default   => ucfirst($value),
                                };
                                $normalizedRoles[$value] = $label;
                            } elseif (is_string($key) && is_string($role)) {
                                // Si viene como ['admin' => 'Administrador']
                                $normalizedRoles[$key] = $role;
                            } elseif (is_string($role)) {
                                // Si viene como ['admin', 'support']
                                $normalizedRoles[$role] = ucfirst($role);
                            }
                        }
                    }

                    // Valor seleccionado por old() o por usuario editado
                    $selectedRole = old('role', $user->role ?? '');
                @endphp

                <select name="role" id="role"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required>
                    <option value="">Selecciona un rol</option>
                    @foreach($normalizedRoles as $value => $label)
                        <option value="{{ $value }}" {{ $selectedRole === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    ← Regresar al Dashboard
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white font-semibold rounded hover:bg-indigo-600 transition-all">
                    Guardar
                </button>
            </div>
        </form>
    </div>

</body>
</html>

