@extends('layouts.ticket-system')

@section('title', 'Nueva Categoría')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Nueva Categoría</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="mt-3">
        @csrf
        <div class="row">
            <div class="col-6">
                <label>Nombre</label>
                <input type="text" name="nombre" class="input" value="{{ old('nombre') }}" required>
            </div>
            <div class="col-6">
                <label>Color</label>
                <input type="color" name="color" class="input" value="{{ old('color', '#3498db') }}" required>
            </div>
            <div class="col-12">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" class="input">{{ old('descripcion') }}</textarea>
            </div>
            <div class="col-6">
                <label>
                    <input type="checkbox" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }}>
                    Activa
                </label>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('categories.index') }}" class="btn secondary">Cancelar</a>
            <button type="submit" class="btn">Crear Categoría</button>
        </div>
    </form>
</div>
@endsection
