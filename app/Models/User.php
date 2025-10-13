<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'company_name',
        'supervisor_email',
        'logo_path',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
        'role' => \App\Enums\UserRole::class,
    ];

    // Nombre completo
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // Valor seguro del rol
    public function getRoleNameAttribute(): string
    {
        return $this->role instanceof UserRole
            ? $this->role->value
            : ($this->role ?? 'desconocido');
    }

    // -----------------------------
    // NUEVOS MÉTODOS PARA LA VISTA
    // -----------------------------

    /**
     * Retorna el rol capitalizado para mostrar en la vista.
     */
    public function getRoleLabelAttribute(): string
    {
        return ucfirst($this->roleName);
    }

    /**
     * Retorna la clase Tailwind para el color según el rol.
     */
    public function getRoleColorAttribute(): string
    {
        switch ($this->roleName) {
            case 'admin':
                return 'bg-red-100 text-red-800';
            case 'user':
                return 'bg-blue-100 text-blue-800';
            case 'superadmin':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    // Relaciones
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_user_id');
    }

    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'client_id');
    }

    public function timeEntries()
    {
        return $this->hasMany(TicketTimeEntry::class, 'technician_id');
    }

    public function clientPlans()
    {
        return $this->hasMany(ClientPlan::class);
    }
}
