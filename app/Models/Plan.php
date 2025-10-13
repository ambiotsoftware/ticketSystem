<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'hours_included',
        'plan_cost',
        'extra_hour_rate',
        'billing_cycle',
        'active'
    ];

    protected $casts = [
        'plan_cost' => 'decimal:2',
        'extra_hour_rate' => 'decimal:2',
        'active' => 'boolean'
    ];

    // Relaciones
    public function clientPlans(): HasMany
    {
        return $this->hasMany(ClientPlan::class);
    }
}
