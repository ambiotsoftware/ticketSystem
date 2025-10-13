<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            <i class="fa-solid fa-user-plus text-indigo-500 mr-2"></i>
            Crear Usuario
        </h2>

        {{-- ✅ Mensaje de éxito --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- ⚠️ Errores de validación --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 🧾 Formulario de creación --}}
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="first_name" class="block text-gray-700 font-medium mb-1">Nombre</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="last_name" class="block text-gray-700 font-medium mb-1">Apellido</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" placeholder="Ingrese la contraseña" required>
            </div>

            {{-- 🔹 Campo de Rol --}}
            <div>
                <label for="role" class="block text-gray-700 font-medium mb-1">Rol</label>
                <select name="role" id="role" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400" required>
                    <option value="">Seleccione un rol</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                    <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Técnico</option>
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Regresar al Dashboard
                </a>
                <button type="submit" class="flex items-center px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar
                </button>
            </div>
        </form>
    </div>

</body>
</html>
