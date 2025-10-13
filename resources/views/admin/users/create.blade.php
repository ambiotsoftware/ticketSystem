@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3"><i class="bi bi-person-plus-fill"></i> Nuevo Usuario</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i> Corrige los siguientes errores:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @include('admin.users._form', ['user' => null])
    </form>
</div>
@endsection
