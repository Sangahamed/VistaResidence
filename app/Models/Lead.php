<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'property_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'status',
        'source',
        'notes',
        'budget_min',
        'budget_max',
        'preferred_location',
        'bedrooms',
        'bathrooms',
        'last_contacted_at',
        'converted_at',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'last_contacted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }

    public function scopeNegotiation($query)
    {
        return $query->where('status', 'negotiation');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeByAgency($query, $agencyId)
    {
        return $query->whereHas('agent', function ($q) use ($agencyId) {
            $q->where('agency_id', $agencyId);
        });
    }

    public function scopeFromWebsite($query)
    {
        return $query->where('source', 'website');
    }

    public function scopeFromReferral($query)
    {
        return $query->where('source', 'referral');
    }

    public function scopeFromSocialMedia($query)
    {
        return $query->where('source', 'social_media');
    }

    public function scopeFromEmailCampaign($query)
    {
        return $query->where('source', 'email_campaign');
    }

    public function scopeFromPhone($query)
    {
        return $query->where('source', 'phone');
    }

    
}