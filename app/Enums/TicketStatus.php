<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'abierto';
    case IN_PROGRESS = 'en_progreso';
    case RESOLVED = 'resuelto';
    case CLOSED = 'cerrado';
    case TRACKING = 'en_seguimiento';
    case PAUSED = 'pausado';
}
