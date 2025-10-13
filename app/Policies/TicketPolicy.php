<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserRole;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === UserRole::ADMIN) {
            // Los administradores pueden hacer todo excepto crear tickets
            if ($ability !== 'create') {
                return true;
            }
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos los roles pueden ver la lista, pero el controlador la filtra
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->client_id || $user->id === $ticket->assigned_user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::CLIENT;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->client_id || $user->id === $ticket->assigned_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return false; // Solo admins pueden borrar, manejado en before()
    }

    /**
     * Determine whether the user can assign the ticket.
     */
    public function assign(User $user): bool
    {
        return false; // Solo admins pueden asignar, manejado en before()
    }
}
