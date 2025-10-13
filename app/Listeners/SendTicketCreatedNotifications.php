<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Services\NotificationService;

class SendTicketCreatedNotifications
{
    /**
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create the event listener.
     *
     * @param \App\Services\NotificationService $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TicketCreated  $event
     * @return void
     */
    public function handle(TicketCreated $event)
    {
        $this->notificationService->notifyTicketCreated($event->ticket);
    }
}
