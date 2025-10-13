<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Tickets Networks Mayan - @yield('title', 'Dashboard')</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 0; background: #f6f7fb; color: #1f2937; }
        header { background: #0f172a; color: white; padding: 14px 20px; }
        header nav { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
        .nav-left, .nav-right { display: flex; align-items: center; }
        .logo { color: #cbd5e1; font-size: 20px; font-weight: 700; margin-right: 32px; letter-spacing: 1px; }
        header a { color: #cbd5e1; text-decoration: none; margin-right: 16px; padding: 8px 12px; border-radius: 4px; transition: all 0.2s; }
        header a.active, header a:hover { color: white; background: rgba(255,255,255,0.1); }
        .logout-btn { background: none; border: none; color: #cbd5e1; cursor: pointer; font-size: inherit; font-family: inherit; padding: 8px 12px; border-radius: 4px; margin-left: 8px; }
        .logout-btn:hover { color: white; background: rgba(255,255,255,0.1); }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: background 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .btn.secondary { background: #64748b; } .btn.secondary:hover { background: #475569; }
        .btn.success { background: #16a34a; } .btn.success:hover { background: #15803d; }
        .btn.warning { background: #f59e0b; } .btn.warning:hover { background: #d97706; }
        .btn.danger { background: #dc2626; } .btn.danger:hover { background: #b91c1c; }
        .btn.small { padding: 6px 12px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input, select, textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .row { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; margin-bottom: 16px; }
        .col-3 { grid-column: span 3; } .col-4 { grid-column: span 4; } .col-6 { grid-column: span 6; } .col-8 { grid-column: span 8; } .col-12 { grid-column: span 12; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center; }
        .stat-number { font-size: 36px; font-weight: 700; color: #2563eb; }
        .stat-label { font-size: 14px; color: #64748b; margin-top: 4px; }
        .tag { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 500; text-transform: uppercase; }
        .tag.abierto { background: #dcfce7; color: #16a34a; }
        .tag.en_seguimiento { background: #dbeafe; color: #2563eb; }
        .tag.pausado { background: #fef3c7; color: #f59e0b; }
        .tag.cerrado { background: #f3f4f6; color: #64748b; }
        .priority-high { color: #dc2626; font-weight: 600; }
        .priority-medium { color: #f59e0b; font-weight: 600; }
        .priority-low { color: #16a34a; font-weight: 600; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert.success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
        .alert.error { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }
        .alert.warning { background: #fef3c7; border: 1px solid #fed7aa; color: #92400e; }
        .mt-4 { margin-top: 16px; } .mb-4 { margin-bottom: 16px; }
        .text-center { text-align: center; }
        .font-semibold { font-weight: 600; }
        .text-sm { font-size: 14px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 8px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-header { padding: 20px 24px 0; border-bottom: 1px solid #e5e7eb; }
        .modal-body { padding: 20px 24px; }
        .modal-footer { padding: 0 24px 20px; text-align: right; }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="nav-left">
            <div class="logo">NETWORKS MAYAN</div>
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                
                @if(auth()->user()->role === 'client')
                    <a href="{{ route('tickets.create') }}" class="{{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        Crear Ticket
                    </a>
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.index', 'tickets.show') ? 'active' : '' }}">
                        Estado del Ticket
                    </a>
                    <a href="{{ route('client.plan') }}" class="{{ request()->routeIs('client.plan') ? 'active' : '' }}">
                        Mi Plan/Servicio
                    </a>
                @elseif(auth()->user()->role === 'technician')
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        Mis Tickets
                    </a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.index', 'tickets.show', 'tickets.edit') ? 'active' : '' }}">
                        Todos los Tickets
                    </a>
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        Categorías
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        📧 Notificaciones
                    </a>
                @endif
            @endauth
        </div>
        @auth
            <div class="nav-right">
                <span style="color: #cbd5e1; margin-right: 16px;">
                    {{ auth()->user()->first_name ?? auth()->user()->name }} 
                    @if(auth()->user()->company_name)
                        - {{ auth()->user()->company_name }}
                    @endif
                </span>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    Mi Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Cerrar Sesión</button>
                </form>
            </div>
        @endauth
    </nav>
</header>

<main class="container">
    @include('partials.flash')
    @yield('content')
</main>

<script>
// Modal functionality
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}
// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});
</script>
</body>
</html>
