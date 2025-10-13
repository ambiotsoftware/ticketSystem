<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;

class Ticket extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'category_id',
        'assigned_user_id',
        'client_id',
        'fecha_limite'
    ];

        protected $casts = [
        'fecha_limite' => 'datetime',
        'estado' => TicketStatus::class,
        'prioridad' => TicketPriority::class,
        'client_id' => 'integer',
        'assigned_user_id' => 'integer',
        'category_id' => 'integer'
    ];

    // Relación con Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con User (asignado)
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Relación con User (cliente que creó el ticket)
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relación con TicketTimeEntry
    public function timeEntries()
    {
        return $this->hasMany(TicketTimeEntry::class);
    }

    // Relación con TicketAttachment
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function emailNotifications(): HasMany
    {
        return $this->hasMany(EmailNotification::class);
    }
}
