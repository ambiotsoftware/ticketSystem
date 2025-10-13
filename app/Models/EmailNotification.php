<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailNotification extends Model
{
    protected $fillable = [
        'ticket_id',
        'recipient_email',
        'recipient_name',
        'recipient_role',
        'subject',
        'body',
        'type',
        'sent',
        'sent_at',
        'metadata'
    ];

    protected $casts = [
        'sent' => 'boolean',
        'sent_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function scopePending($query)
    {
        return $query->where('sent', false);
    }

    public function scopeSent($query)
    {
        return $query->where('sent', true);
    }

    public function markAsSent()
    {
        $this->update([
            'sent' => true,
            'sent_at' => now()
        ]);
    }
}
