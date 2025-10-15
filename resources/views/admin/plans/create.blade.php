@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header with-border">
                        <h1 class="card-title fs-3">
                            <i class="bi bi-person-plus-fill"></i> {{  __('Nuevo Plan') }}
                        </h1>
                    </div>
                    <form method="POST" action="{{ route('admin.plans.store') }}">
                        @include('admin.plans._form', ['btnText' => __('Crear Plan')])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
