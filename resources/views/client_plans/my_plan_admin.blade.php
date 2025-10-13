@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Planes de Todos los Clientes</h2>

    @if(!empty($plans) && count($plans) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Plan</th>
                    <th>Descripci¨®n</th>
                    <th>Asignado el</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $clientPlan)
                    <tr>
                        <td>{{ $clientPlan->user->name ?? 'N/A' }}</td>
                        <td>{{ $clientPlan->plan->name ?? 'N/A' }}</td>
                        <td>{{ $clientPlan->plan->description ?? 'N/A' }}</td>
                        <td>{{ $clientPlan->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay planes asignados a ning¨²n cliente.</div>
    @endif
</div>
@endsection
