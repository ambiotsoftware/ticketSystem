@extends('layouts.ticket-system')

@section('title', 'Categorías')

@section('content')
<div class="card">
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px;">
        <h1 style="margin:0;">Categorías</h1>
        <a class="btn" href="{{ route('categories.create') }}">+ Nueva categoría</a>
    </div>

    @if($categories->count() === 0)
        <p>No hay categorías aún.</p>
    @else
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Color</th>
                    <th>Activa</th>
                    <th>Tickets</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->nombre }}</td>
                        <td>{{ $c->descripcion }}</td>
                        <td><span class="tag" style="background: {{ $c->color }}; color: #fff;">{{ $c->color }}</span></td>
                        <td>{{ $c->activa ? 'Sí' : 'No' }}</td>
                        <td>{{ $c->tickets_count }}</td>
                        <td style="white-space:nowrap;">
                            <a class="btn secondary" href="{{ route('categories.show', $c) }}">Ver</a>
                            <a class="btn secondary" href="{{ route('categories.edit', $c) }}">Editar</a>
                            <form action="{{ route('categories.destroy', $c) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

