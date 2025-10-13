<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'time_entry_id',
        'uploaded_by',
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'description',
    ];
}
