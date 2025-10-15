@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header with-border">
                        <h1 class="card-title fs-3">
                            <i class="bi bi-person-plus-fill"></i> {{  __('Editar asignación') }}
                        </h1>
                    </div>
                    <form method="POST" action="{{ route('admin.client-plans.update', $clientPlan) }}">
                        @method('PUT')
                        @include('admin.client-plans._form', ['btnText' => __('Actualizar asignación')])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
