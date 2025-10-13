<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketTimeEntry;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Enums\TicketStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case UserRole::CLIENT:
                return $this->clientDashboard();
            case UserRole::TECHNICIAN:
                return $this->technicianDashboard();
            case UserRole::ADMIN:
                return $this->adminDashboard();
            default:
                abort(403, 'Rol no reconocido');
        }
    }

    private function clientDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_tickets' => Ticket::where('client_id', $user->id)->count(),
            'open_tickets' => Ticket::where('client_id', $user->id)->where('estado', TicketStatus::OPEN)->count(),
            'in_progress' => Ticket::where('client_id', $user->id)->where('estado', TicketStatus::TRACKING)->count(),
            'closed_tickets' => Ticket::where('client_id', $user->id)->where('estado', TicketStatus::CLOSED)->count()
        ];
        
        $recent_tickets = Ticket::where('client_id', $user->id)
            ->with(['category', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('dashboard.client', compact('stats', 'recent_tickets'));
    }

    private function technicianDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'assigned_tickets' => Ticket::where('assigned_user_id', $user->id)->count(),
            'active_tickets' => Ticket::where('assigned_user_id', $user->id)
                ->whereIn('estado', [TicketStatus::OPEN, TicketStatus::TRACKING])->count(),
            'paused_tickets' => Ticket::where('assigned_user_id', $user->id)
                ->where('estado', TicketStatus::PAUSED)->count(),
            'completed_today' => Ticket::where('assigned_user_id', $user->id)
                ->where('estado', TicketStatus::CLOSED)
                ->whereDate('updated_at', today())->count()
        ];
        
        $assigned_tickets = Ticket::where('assigned_user_id', $user->id)
            ->whereIn('estado', [TicketStatus::OPEN, TicketStatus::TRACKING, TicketStatus::PAUSED])
            ->with(['category', 'client'])
            ->withExists(['timeEntries as has_active_time_entry' => function ($query) {
                $query->where('status', 'started');
            }])
            ->orderBy('prioridad', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('dashboard.technician', compact('stats', 'assigned_tickets'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('estado', TicketStatus::OPEN)->count(),
            'unassigned_tickets' => Ticket::whereNull('assigned_user_id')->count(),
            'active_technicians' => User::where('role', UserRole::TECHNICIAN)->where('active', true)->count(),
            'total_clients' => User::where('role', UserRole::CLIENT)->where('active', true)->count()
        ];
        
        $recent_tickets = Ticket::with(['category', 'client', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $technician_workload = User::where('role', UserRole::TECHNICIAN)
            ->where('active', true)
            ->withCount([
                'assignedTickets as active_tickets' => function($query) {
                    $query->whereIn('estado', [TicketStatus::OPEN, TicketStatus::TRACKING, TicketStatus::PAUSED]);
                }
            ])
            ->get();
            
        // 🔧 Corrección: ordenar por first_name y last_name en lugar de name
        $technicians = User::where('role', UserRole::TECHNICIAN)
            ->where('active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
            
        return view('dashboard.admin', compact('stats', 'recent_tickets', 'technician_workload', 'technicians'));
    }
}
