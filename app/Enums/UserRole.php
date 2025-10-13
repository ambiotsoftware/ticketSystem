<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case TECHNICIAN = 'technician';
    
    public function trans()
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::CLIENT => 'Cliente',
            self::TECHNICIAN => 'Tecnico',
        };
    }
}
