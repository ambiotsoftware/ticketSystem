@extends('layouts.ticket-system')

@section('title', 'Mi Plan/Servicio Contratado')

@section('content')
<div class="card">
    <h1 style="margin-top: 0; color: #1f2937;">Mi Plan/Servicio Contratado</h1>
    <p style="color: #64748b;">Monitorea el consumo de horas de tu plan de soporte técnico.</p>
</div>

@if(!$has_plan)
    <div class="card text-center" style="padding: 60px;">
        <div style="font-size: 48px; margin-bottom: 20px;">📋</div>
        <h2 style="color: #64748b; margin-bottom: 16px;">Sin Plan Activo</h2>
        <p style="color: #64748b; margin-bottom: 24px;">{{ $message }}</p>
        <a href="mailto:admin@avenir-support.com" class="btn">Contactar Administrador</a>
    </div>
@else
<div class="row">
    <div class="col-8">
        <div class="card">
            <h2 style="margin-top: 0;">{{ $plan->name }}</h2>
            <p style="color: #64748b; margin-bottom: 24px;">{{ $plan->description }}</p>
            
            <!-- Barra de Progreso de Horas -->
            <div style="background: #f3f4f6; border-radius: 8px; height: 24px; margin-bottom: 16px; overflow: hidden;">
                <div style="background: linear-gradient(90deg, #16a34a 0%, #f59e0b 70%, #dc2626 100%); height: 100%; width: {{ $stats['usage_percentage'] }}%; transition: width 0.3s ease;"></div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" style="color: #2563eb;">{{ $stats['hours_used'] }}</div>
                    <div class="stat-label">Horas Consumidas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #16a34a;">{{ $stats['hours_remaining'] }}</div>
                    <div class="stat-label">Horas Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #64748b;">{{ $stats['hours_included'] }}</div>
                    <div class="stat-label">Horas Incluidas</div>
                </div>
                @if($stats['extra_hours'] > 0)
                <div class="stat-card">
                    <div class="stat-number" style="color: #dc2626;">{{ $stats['extra_hours'] }}</div>
                    <div class="stat-label">Horas Extras</div>
                </div>
                @endif
            </div>
            
            <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-top: 20px;">
                <div class="row">
                    <div class="col-6">
                        <p style="margin: 0; color: #64748b; font-size: 14px;">Progreso del plan</p>
                        <p style="margin: 0; font-weight: 600; font-size: 18px;">{{ $stats['usage_percentage'] }}% utilizado</p>
                    </div>
                    <div class="col-6">
                        <p style="margin: 0; color: #64748b; font-size: 14px;">Periodo activo</p>
                        <p style="margin: 0; font-weight: 600; font-size: 14px;">
                            {{ $period['start']->format('d/m/Y') }} - {{ $period['end']->format('d/m/Y') }}
                            @if($period['days_remaining'] >= 0)
                                <span style="color: #16a34a;">({{ $period['days_remaining'] }} días restantes)</span>
                            @else
                                <span style="color: #dc2626;">(Vencido)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($recent_tickets->count() > 0)
        <div class="card">
            <h3 style="margin-top: 0;">Tickets del Periodo Actual</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Técnico</th>
                        <th>Tiempo Utilizado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" style="color: #2563eb; text-decoration: none;">
                                {{ Str::limit($ticket->titulo, 30) }}
                            </a>
                        </td>
                        <td><span class="tag {{ $ticket->estado }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}</span></td>
                        <td>{{ $ticket->assignedUser->name ?? '-' }}</td>
                        <td>
                            @php
                                $ticketMinutes = $ticket->timeEntries->sum('duration_minutes');
                                $ticketHours = round($ticketMinutes / 60, 2);
                            @endphp
                            {{ $ticketHours }}h
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
    <div class="col-4">
        <div class="card">
            <h3 style="margin-top: 0;">💰 Resumen de Costos</h3>
            
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Costo del Plan:</span>
                    <span style="font-weight: 600;">${{ number_format($costs['plan_cost'], 2) }}</span>
                </div>
                <div style="font-size: 12px; color: #64748b;">
                    Incluye {{ $stats['hours_included'] }} horas de soporte
                </div>
            </div>
            
            @if($stats['extra_hours'] > 0)
            <div style="margin-bottom: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: #dc2626;">Horas Extras ({{ $stats['extra_hours'] }}h):</span>
                    <span style="font-weight: 600; color: #dc2626;">${{ number_format($costs['extra_hours_cost'], 2) }}</span>
                </div>
                <div style="font-size: 12px; color: #64748b;">
                    Tarifa: ${{ number_format($costs['extra_hour_rate'], 2) }} por hora
                </div>
            </div>
            @endif
            
            <div style="padding-top: 16px; border-top: 2px solid #2563eb;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-weight: 600; font-size: 18px;">Total a Pagar:</span>
                    <span style="font-weight: 700; font-size: 24px; color: #2563eb;">${{ number_format($costs['total_cost'], 2) }}</span>
                </div>
                @if($stats['extra_hours'] > 0)
                    <div style="font-size: 12px; color: #dc2626;">
                        ⚠️ Has excedido tu plan en {{ $stats['extra_hours'] }} horas
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-top: 0;">📊 Detalles del Plan</h3>
            <div style="font-size: 14px; line-height: 1.6;">
                <div style="margin-bottom: 12px;">
                    <strong>Ciclo de Facturación:</strong><br>
                    {{ ucfirst($plan->billing_cycle) === 'Monthly' ? 'Mensual' : ($plan->billing_cycle === 'Quarterly' ? 'Trimestral' : 'Anual') }}
                </div>
                <div style="margin-bottom: 12px;">
                    <strong>Horas Incluidas:</strong><br>
                    {{ $stats['hours_included'] }} horas por {{ $plan->billing_cycle === 'monthly' ? 'mes' : ($plan->billing_cycle === 'quarterly' ? 'trimestre' : 'año') }}
                </div>
                <div style="margin-bottom: 12px;">
                    <strong>Tarifa Hora Extra:</strong><br>
                    ${{ number_format($costs['extra_hour_rate'], 2) }} USD
                </div>
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-top: 0;">🔧 Acciones Rápidas</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('tickets.create') }}" class="btn">📝 Crear Nuevo Ticket</a>
                <a href="{{ route('tickets.index') }}" class="btn secondary">📋 Ver Mis Tickets</a>
                <a href="mailto:admin@avenir-support.com" class="btn secondary">💬 Contactar Soporte</a>
            </div>
        </div>
        
        @if($stats['usage_percentage'] > 80)
        <div class="alert warning">
            <strong>⚠️ Advertencia:</strong> Has consumido el {{ $stats['usage_percentage'] }}% de tu plan. 
            @if($stats['usage_percentage'] >= 100)
                Las siguientes horas se facturarán como extras.
            @else
                Considera optimizar el uso o contactar para cambiar de plan.
            @endif
        </div>
        @endif
    </div>
</div>
@endif
@endsection
