@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mis Planes</h2>

    @if(isset($message))
        <div class="alert alert-info">{{ $message }}</div>
    @endif

    @if(!empty($plans) && count($plans) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Plan</th>
                    <th>Descripci¨®n</th>
                    <th>Fecha de asignaci¨®n</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $clientPlan)
                    <tr>
                        <td>{{ $clientPlan->plan->name ?? 'N/A' }}</td>
                        <td>{{ $clientPlan->plan->description ?? 'N/A' }}</td>
                        <td>{{ $clientPlan->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
