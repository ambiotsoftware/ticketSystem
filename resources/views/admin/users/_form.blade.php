{{-- resources/views/admin/users/_form.blade.php --}}

@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">
        {{ isset($user) ? 'Editar Usuario' : 'Nuevo Usuario' }}
    </h2>

    {{-- ✅ Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Mostrar mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form
        action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
        method="POST"
    >
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">Nombre</label>
                <input type="text" name="first_name" id="first_name"
                       class="form-control"
                       value="{{ old('first_name', $user->first_name ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" name="last_name" id="last_name"
                       class="form-control"
                       value="{{ old('last_name', $user->last_name ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email"
                       class="form-control"
                       value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Rol</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="admin" {{ old('role', $user->role->value ?? '') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="technician" {{ old('role', $user->role->value ?? '') == 'technician' ? 'selected' : '' }}>Tecnico</option>
                    <option value="client" {{ old('role', $user->role->value ?? '') == 'client' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>

            {{-- ✅ Campo contraseña solo cuando se crea o si se desea cambiar --}}
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">
                    {{ isset($user) ? 'Nueva Contraseña (opcional)' : 'Contraseña' }}
                </label>
                <input type="password" name="password" id="password"
                       class="form-control" {{ isset($user) ? '' : 'required' }}>
            </div>
            {{-- Company Name --}}
            <div class="col-md-6 mb-3" id="company_name_wrapper" style="display: none;">
                <label for="company_name" class="form-label">Nombre de la Empresa</label>
                <input
                    type="text"
                    name="company_name"
                    id="company_name"
                    class="form-control"
                    value="{{ old('company_name', $user->company_name ?? '') }}"
                    placeholder="Ingrese el nombre de la empresa">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">
                {{ isset($user) ? 'Actualizar' : 'Guardar' }}
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            const $role = $('#role');
            const $companyWrapper = $('#company_name_wrapper');
            const $companyInput = $('#company_name');

            function toggleCompanyField() {
                if ($role.val() === 'client') {
                    $companyWrapper.show();
                    $companyInput.prop('required', true);
                } else {
                    $companyWrapper.hide();
                    $companyInput.prop('required', false).val('');
                }
            }

            // Inicialización (en caso de edición)
            toggleCompanyField();

            // Evento cuando cambia el rol
            $role.on('change', toggleCompanyField);
        });
    </script>
@endsection
