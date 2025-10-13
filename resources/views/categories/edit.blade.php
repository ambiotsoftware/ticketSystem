@extends('layouts.ticket-system')

@section('title', 'Editar Categoría')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Editar Categoría: {{ $category->nombre }}</h1>

    <form method="POST" action="{{ route('categories.update', $category) }}" class="mt-3">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-6">
                <label>Nombre</label>
                <input type="text" name="nombre" class="input" value="{{ old('nombre', $category->nombre) }}" required>
            </div>
            <div class="col-6">
                <label>Color</label>
                <input type="color" name="color" class="input" value="{{ old('color', $category->color) }}" required>
            </div>
            <div class="col-12">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" class="input">{{ old('descripcion', $category->descripcion) }}</textarea>
            </div>
            <div class="col-6">
                <label>
                    <input type="checkbox" name="activa" value="1" {{ old('activa', $category->activa) ? 'checked' : '' }}>
                    Activa
                </label>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('categories.show', $category) }}" class="btn secondary">Cancelar</a>
            <button type="submit" class="btn">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
