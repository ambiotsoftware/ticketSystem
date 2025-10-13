<?php

namespace App\Enums;

enum TicketPriority: string
{
    case LOW = 'baja';
    case MEDIUM = 'media';
    case HIGH = 'alta';
    case CRITICAL = 'critica';
}
