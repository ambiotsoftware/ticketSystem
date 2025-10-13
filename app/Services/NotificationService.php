<?php

namespace App\Services;

use App\Models\EmailNotification;
use App\Models\Ticket;
use App\Models\User;
use App\Mail\GenericNotificationMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Crear notificaciones cuando se registra un nuevo ticket
     */
    public function notifyTicketCreated(Ticket $ticket)
    {
        $notifications = [];

        // 1. Notificar al cliente que registró el ticket
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $ticket->client->email,
            'recipient_name' => $ticket->client->name,
            'recipient_role' => 'client',
            'subject' => "Ticket #{$ticket->id} registrado exitosamente",
            'body' => $this->getClientNotificationBody($ticket),
            'type' => 'ticket_created_client'
        ]);

        // 2. Notificar a Hans Higueros (admin principal)
        $hans = User::where('email', 'admin@avenir-support.com')->first();
        if ($hans) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $hans->email,
                'recipient_name' => $hans->name,
                'recipient_role' => 'hans',
                'subject' => "Nuevo ticket #{$ticket->id} registrado",
                'body' => $this->getHansNotificationBody($ticket),
                'type' => 'ticket_created_hans'
            ]);
        }

        // 3. Notificar a otros administradores
        $other_admins = User::where('role', 'admin')
            ->where('email', '!=', 'katoli5419@etenx.com')
            ->where('active', true)
            ->get();

        foreach ($other_admins as $admin) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $admin->email,
                'recipient_name' => $admin->name,
                'recipient_role' => 'admin',
                'subject' => "Nuevo ticket #{$ticket->id} requiere asignación",
                'body' => $this->getAdminNotificationBody($ticket),
                'type' => 'ticket_created_admin'
            ]);
        }

        return $notifications;
    }

    /**
     * Crear notificaciones cuando un ticket es asignado
     */
    public function notifyTicketPaused(Ticket $ticket, User $technician, string $reason)
    {
        $notifications = [];

        // Notificar al cliente
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $ticket->client->email,
            'recipient_name' => $ticket->client->name,
            'recipient_role' => 'client',
            'subject' => "Actualización del ticket #{$ticket->id} - Trabajo pausado",
            'body' => $this->getPausedClientBody($ticket, $technician, $reason),
            'type' => 'ticket_paused_client'
        ]);

        // Notificar a los administradores
        $admins = User::where('role', 'admin')->where('active', true)->get();
        foreach ($admins as $admin) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $admin->email,
                'recipient_name' => $admin->name,
                'recipient_role' => 'admin',
                'subject' => "Ticket #{$ticket->id} pausado por {$technician->name}",
                'body' => $this->getPausedAdminBody($ticket, $technician, $reason),
                'type' => 'ticket_paused_admin'
            ]);
        }

        return $notifications;
    }

    public function notifyTicketResolved(Ticket $ticket, User $technician, string $resolution_comment)
    {
        $notifications = [];

        // Notificar al cliente
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $ticket->client->email,
            'recipient_name' => $ticket->client->name,
            'recipient_role' => 'client',
            'subject' => "Ticket #{$ticket->id} ha sido resuelto",
            'body' => $this->getResolvedClientBody($ticket, $technician, $resolution_comment),
            'type' => 'ticket_resolved_client'
        ]);

        // Notificar a los administradores
        $admins = User::where('role', 'admin')->where('active', true)->get();
        foreach ($admins as $admin) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $admin->email,
                'recipient_name' => $admin->name,
                'recipient_role' => 'admin',
                'subject' => "Ticket #{$ticket->id} resuelto por {$technician->name}",
                'body' => $this->getResolvedAdminBody($ticket, $technician, $resolution_comment),
                'type' => 'ticket_resolved_admin'
            ]);
        }

        return $notifications;
    }

    public function notifyTicketStarted(Ticket $ticket, User $technician)
    {
        $notifications = [];

        // Notificar al cliente
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $ticket->client->email,
            'recipient_name' => $ticket->client->name,
            'recipient_role' => 'client',
            'subject' => "El trabajo en su ticket #{$ticket->id} ha comenzado",
            'body' => $this->getStartedClientBody($ticket, $technician),
            'type' => 'ticket_started_client'
        ]);

        // Notificar a los administradores
        $admins = User::where('role', 'admin')->where('active', true)->get();
        foreach ($admins as $admin) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $admin->email,
                'recipient_name' => $admin->name,
                'recipient_role' => 'admin',
                'subject' => "Inicio de trabajo en ticket #{$ticket->id}",
                'body' => $this->getStartedAdminBody($ticket, $technician),
                'type' => 'ticket_started_admin'
            ]);
        }

        return $notifications;
    }

    public function notifyTicketReassigned(Ticket $ticket, User $technician, string $reason)
    {
        $notifications = [];
        $admins = User::where('role', 'admin')->where('active', true)->get();

        foreach ($admins as $admin) {
            $notifications[] = $this->createNotification($ticket, [
                'recipient_email' => $admin->email,
                'recipient_name' => $admin->name,
                'recipient_role' => 'admin',
                'subject' => "Ticket #{$ticket->id} devuelto a la cola",
                'body' => $this->getReassignedAdminBody($ticket, $technician, $reason),
                'type' => 'ticket_reassigned_admin'
            ]);
        }

        return $notifications;
    }

    public function notifyTicketAssigned(Ticket $ticket, User $assignedTechnician, User $assignedBy)
    {
        $notifications = [];

        // Notificar al técnico asignado
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $assignedTechnician->email,
            'recipient_name' => $assignedTechnician->name,
            'recipient_role' => 'technician',
            'subject' => "Te han asignado el ticket #{$ticket->id}",
            'body' => $this->getTechnicianAssignmentBody($ticket, $assignedBy),
            'type' => 'ticket_assigned_technician'
        ]);

        // Notificar al cliente sobre la asignación
        $notifications[] = $this->createNotification($ticket, [
            'recipient_email' => $ticket->client->email,
            'recipient_name' => $ticket->client->name,
            'recipient_role' => 'client',
            'subject' => "Actualización del ticket #{$ticket->id} - Técnico asignado",
            'body' => $this->getClientAssignmentBody($ticket, $assignedTechnician),
            'type' => 'ticket_assigned_client'
        ]);

        return $notifications;
    }

    /**
     * Crear una notificación individual
     */
    private function createNotification(Ticket $ticket, array $data)
    {
        // Guardar en la base de datos
        $notification = EmailNotification::create(array_merge([
            'ticket_id' => $ticket->id,
            'metadata' => [
                'ticket_title' => $ticket->titulo,
                'ticket_priority' => $ticket->prioridad->value,
                'client_company' => $ticket->client->company_name ?? $ticket->client->name,
                'created_at' => now()->toISOString()
            ]
        ], $data));

        // Enviar el correo real
        try {
            Mail::to($data['recipient_email'])->send(new GenericNotificationMail($data['subject'], $data['body']));
        } catch (\Exception $e) {
            // Opcional: registrar el error si el envío falla
            // Log::error("Failed to send notification email: " . $e->getMessage());
        }

        return $notification;
    }

    /**
     * Plantilla de correo para cliente - ticket creado
     */
    private function getClientNotificationBody(Ticket $ticket)
    {
        return "
Estimado/a {$ticket->client->name},
Su ticket ha sido registrado exitosamente en nuestro sistema de soporte técnico.

Detalles del ticket:
- ID: #{$ticket->id}
- Título: {$ticket->titulo}
- Prioridad: " . ucfirst($ticket->prioridad->value) . "
- Estado: " . ucfirst($ticket->estado->value) . "
- Fecha de registro: {$ticket->created_at->format('d/m/Y H:i')}

Descripción:
{$ticket->descripcion}

{{ ... }}
Le mantendremos informado sobre el progreso.

Puede hacer seguimiento a su ticket accediendo a nuestro portal en cualquier momento.

Saludos cordiales,
Equipo de Soporte Técnico
";
    }

    /**
     * Plantilla de correo para Hans - ticket creado
     */
    private function getHansNotificationBody(Ticket $ticket)
    {
        return "
Hans,

Se ha registrado un nuevo ticket en el sistema que requiere tu conocimiento.

Detalles del ticket:
- ID: #{$ticket->id}
- Cliente: {$ticket->client->company_name} ({$ticket->client->name})
- Email: {$ticket->client->email}
- Título: {$ticket->titulo}
- Prioridad: " . ucfirst($ticket->prioridad->value) . "
- Categoría: " . ($ticket->category->nombre ?? 'Sin categoría') . "
- Fecha de registro: {$ticket->created_at->format('d/m/Y H:i')}

Descripción del problema:
{$ticket->descripcion}

El ticket está pendiente de asignación.

Accede al panel de administración para revisar y tomar acción.

Sistema de Tickets
";
    }

    /**
     * Plantilla de correo para supervisores - ticket creado
     */
    private function getAdminNotificationBody(Ticket $ticket)
    {
        return "
Estimado/a Administrador,

Se ha registrado un nuevo ticket que requiere asignación a un técnico.

Detalles del ticket:
- ID: #{$ticket->id}
- Cliente: {$ticket->client->company_name} ({$ticket->client->name})
- Título: {$ticket->titulo}
- Prioridad: " . ucfirst($ticket->prioridad->value) . "
- Categoría: " . ($ticket->category->nombre ?? 'Sin categoría') . "
- Fecha de registro: {$ticket->created_at->format('d/m/Y H:i')}

Descripción:
{$ticket->descripcion}

Por favor, asigne este ticket al técnico más adecuado según la categoría y carga de trabajo.

Acceda al panel de administración para gestionar la asignación.

Sistema de Tickets
";
    }

    /**
     * Plantilla de correo para técnico - ticket asignado
     */
    private function getTechnicianAssignmentBody(Ticket $ticket, User $assignedBy)
    {
        return "
Estimado/a {$ticket->assignedUser->name},

Se le ha asignado un nuevo ticket para su atención.

Detalles del ticket:
- ID: #{$ticket->id}
- Cliente: {$ticket->client->company_name} ({$ticket->client->name})
- Email del cliente: {$ticket->client->email}
- Título: {$ticket->titulo}
- Prioridad: " . ucfirst($ticket->prioridad->value) . "
- Categoría: " . ($ticket->category->nombre ?? 'Sin categoría') . "
- Fecha límite: " . ($ticket->fecha_limite ? $ticket->fecha_limite->format('d/m/Y H:i') : 'No definida') . "
- Asignado por: {$assignedBy->name}

Descripción del problema:
{$ticket->descripcion}

Por favor, inicie el trabajo en este ticket lo antes posible y mantenga informado al cliente sobre el progreso.

Puede acceder al ticket desde el panel de técnico para iniciar el cronómetro y registrar su trabajo.

Equipo de Soporte Técnico
";
    }

    /**
     * Plantilla de correo para cliente - ticket asignado
     */
    private function getPausedClientBody(Ticket $ticket, User $technician, string $reason)
    {
        return "
Estimado/a {$ticket->client->name},

El trabajo en su ticket #{$ticket->id} ha sido pausado temporalmente.

Detalles:
- Técnico: {$technician->name}
- Razón de la pausa: {$reason}

Nos pondremos en contacto con usted tan pronto como se reanude el trabajo.

Saludos cordiales,
Equipo de Soporte Técnico
";
    }

    private function getPausedAdminBody(Ticket $ticket, User $technician, string $reason)
    {
        return "
El ticket #{$ticket->id} ha sido pausado.

- Técnico: {$technician->name}
- Cliente: {$ticket->client->company_name}
- Razón: {$reason}

El estado del ticket se ha actualizado a 'Pausado'.
";
    }

    private function getResolvedClientBody(Ticket $ticket, User $technician, string $resolution_comment)
    {
        return "
Estimado/a {$ticket->client->name},

Nos complace informarle que su ticket #{$ticket->id} ha sido resuelto.

- Técnico: {$technician->name}
- Comentarios de resolución: {$resolution_comment}

Si considera que el problema no ha sido resuelto satisfactoriamente, por favor, responda a este correo o contacte con nosotros.

Saludos cordiales,
Equipo de Soporte Técnico
";
    }

    private function getResolvedAdminBody(Ticket $ticket, User $technician, string $resolution_comment)
    {
        return "
El ticket #{$ticket->id} ha sido marcado como resuelto.

- Técnico: {$technician->name}
- Cliente: {$ticket->client->company_name}
- Resolución: {$resolution_comment}

El ticket está pendiente de cierre final por un administrador.
";
    }

    private function getStartedAdminBody(Ticket $ticket, User $technician)
    {
        return "
El técnico {$technician->name} ha iniciado el trabajo en el ticket #{$ticket->id}.

- Cliente: {$ticket->client->company_name}
- Título: {$ticket->titulo}

El estado del ticket se ha actualizado a 'En Seguimiento'.
";
    }

    private function getStartedClientBody(Ticket $ticket, User $technician)
    {
        return "
Estimado/a {$ticket->client->name},

Le informamos que el técnico {$technician->name} ha comenzado a trabajar en su ticket #{$ticket->id}.

Le mantendremos informado sobre cualquier progreso.

Saludos cordiales,
Equipo de Soporte Técnico
";
    }

    private function getReassignedAdminBody(Ticket $ticket, User $technician, string $reason)
    {
        return "
El técnico {$technician->name} ha devuelto el ticket #{$ticket->id} a la cola de asignación.

- Razón: {$reason}

El ticket ahora está 'Abierto' y requiere ser asignado a otro técnico.
";
    }

    private function getClientAssignmentBody(Ticket $ticket, User $technician)
    {
        return "
Estimado/a {$ticket->client->name},

Su ticket #{$ticket->id} ha sido asignado a un técnico especializado.

Detalles de la asignación:
- Técnico asignado: {$technician->name}
- Email del técnico: {$technician->email}
- Fecha de asignación: " . now()->format('d/m/Y H:i') . "

Su ticket:
- Título: {$ticket->titulo}
- Prioridad: " . ucfirst($ticket->prioridad->value) . "
- Estado actual: En proceso de asignación

El técnico se pondrá en contacto con usted pronto para iniciar el trabajo en su solicitud.
Le mantendremos informado sobre el progreso del ticket.

Puede hacer seguimiento en nuestro portal en cualquier momento.

Saludos cordiales,
Equipo de Soporte Técnico
";
    }

    /**
     * Obtener todas las notificaciones pendientes de envío
     */
    public function getPendingNotifications()
    {
        return EmailNotification::pending()
            ->with('ticket.client')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Marcar notificaciones como enviadas (simulación)
     */
    public function markNotificationsAsSent(array $notificationIds)
    {
        return EmailNotification::whereIn('id', $notificationIds)
            ->update([
                'sent' => true,
                'sent_at' => now()
            ]);
    }
}
