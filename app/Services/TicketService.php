<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Enums\TicketStatus;

class TicketService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Crea un nuevo ticket y gestiona las notificaciones.
     *
     * @param array $data Los datos validados del request.
     * @return Ticket
     */
    public function createTicket(array $data): Ticket
    {
        $user = Auth::user();

        if ($user->role === UserRole::CLIENT) {
            $data['client_id'] = $user->id;
            unset($data['assigned_user_id']);
        } elseif ($user->role === UserRole::ADMIN) {
            $data['client_id'] = $data['client_id'] ?? $user->id;
        }
        
        $data['estado'] = TicketStatus::OPEN;

        $ticket = Ticket::create($data);
        
        $ticket->load(['client', 'category', 'assignedUser']);

        $this->notificationService->notifyTicketCreated($ticket);
        
        if ($ticket->assigned_user_id && $user->role === 'admin') {
            $this->notificationService->notifyTicketAssigned($ticket, $ticket->assignedUser, $user);
        }

        return $ticket;
    }

    /**
     * Asigna un ticket a un técnico y envía la notificación.
     *
     * @param Ticket $ticket
     * @param int $technicianId
     * @return Ticket
     */
    public function assignTicket(Ticket $ticket, int $technicianId): Ticket
    {
        $technician = User::where('id', $technicianId)
            ->where('role', UserRole::TECHNICIAN)
            ->where('active', true)
            ->firstOrFail();

        $ticket->assigned_user_id = $technician->id;
        $ticket->estado = TicketStatus::TRACKING;
        $ticket->save();

        $ticket->load(['client', 'assignedUser', 'category']);
        $this->notificationService->notifyTicketAssigned($ticket, $technician, Auth::user());

        return $ticket;
    }

    public function reassignTicket(Ticket $ticket, string $reason): Ticket
    {
        $ticket->assigned_user_id = null;
        $ticket->estado = TicketStatus::OPEN;
        $ticket->save();

        // $this->notificationService->notifyTicketReassigned($ticket, Auth::user(), $reason);

        return $ticket;
    }
}
