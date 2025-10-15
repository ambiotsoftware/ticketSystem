@extends('layouts.ticket-system')

@section('title', 'Mis Planes')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px;">
            <h1 style="margin:0;">Mis Planes</h1>
        </div>

        @if($clientPlans->count() === 0)
            <p>No hay planes aún.</p>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15em" class="">Plan</th>
                            <th style="width: 10em" class="text-center">Estado</th>
                            <th style="width: 10em" class="text-center">Fecha inicio</th>
                            <th style="width: 10em" class="text-center">Fecha Fin</th>
                            <th style="width: 15em" class="text-center">Costo plan p.</th>
                            <th style="width: 15em" class="text-center">Horas extra p.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientPlans as $clientPlan)
                            <tr>
                                <td class="align-middle">{{ $clientPlan?->plan?->name }}</td>
                                <td class="text-center align-middle">
                                    @if($clientPlan->active == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $clientPlan->start_date?->format('d/m/Y') }}</td>
                                <td class="text-center align-middle">{{ $clientPlan->end_date?->format('d/m/Y') }}</td>
                                <td class="text-center align-middle">{{ $clientPlan->custom_plan_cost ?? '--' }}</td>
                                <td class="text-center align-middle">{{ $clientPlan->custom_extra_hour_rate ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $clientPlans->links() }}
            </div>
        @endif
    </div>
@endsection

