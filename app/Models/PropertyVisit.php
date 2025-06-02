<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyVisit extends Model
{
    use HasFactory;

    protected $fillable = [
    'property_id',
    'visitor_id',
    'agent_id',
    'visit_date',
    'visit_time_start',
    'visit_time_end',
    'scheduled_at',
    'status',
    'notes',
    'title',
    'is_private',
    'confirmation_code'
];

protected $dates = [
    'visit_date',
    'scheduled_at',
    'created_at',
    'updated_at'
];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time_start' => 'datetime',
        'visit_time_end' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_private' => 'boolean',
    ];

    /**
     * Get the property associated with the visit.
     */
    
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the visitor (user) who requested the visit.
     */
    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }

    /**
     * Get the agent assigned to the visit.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Determine if the visit is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Determine if the visit is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Determine if the visit is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Determine if the visit is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Determine if the visit is upcoming.
     */
    public function isUpcoming()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->visit_date->startOfDay()->gte(now()->startOfDay());
    }

    /**
     * Scope a query to only include pending visits.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include confirmed visits.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include completed visits.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled visits.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include upcoming visits.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->whereDate('visit_date', '>=', now()->toDateString());
    }

    /**
     * Scope a query to only include past visits.
     */
    public function scopePast($query)
    {
        return $query->whereDate('visit_date', '<', now()->toDateString())
                     ->orWhere('status', 'completed');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }


    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeForVisitor($query, $visitorId)
    {
        return $query->where('visitor_id', $visitorId);
    }

    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function isPast()
    {
        return $this->visit_date < now()->toDateString();
    }

    public function getTypeAttribute()
    {
        return $this->is_private ? 'Privée' : 'Propriété';
    }
}