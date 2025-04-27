<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'owner_id',
        'tenant_id',
        'type',
        'status',
        'amount',
        'start_date',
        'end_date',
        'payment_frequency',
        'terms',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }
}