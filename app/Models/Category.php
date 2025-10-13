<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Relación con Tickets
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
