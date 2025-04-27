<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'budget',
        'cost',
        'target_audience_size',
        'target_criteria',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'cost' => 'decimal:2',
        'target_audience_size' => 'integer',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(CampaignResult::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function getLeadsCountAttribute(): int
    {
        return $this->leads()->count();
    }

    public function getConversionsCountAttribute(): int
    {
        return $this->leads()->where('status', 'converted')->count();
    }

    public function getConversionRateAttribute(): float
    {
        $leadsCount = $this->leads_count;
        if ($leadsCount === 0) {
            return 0;
        }

        return ($this->conversions_count / $leadsCount) * 100;
    }

    public function getRoiAttribute(): float
    {
        if ($this->cost == 0) {
            return 0;
        }

        $revenue = $this->results()->sum('revenue');
        return (($revenue - $this->cost) / $this->cost) * 100;
    }

    public function getTotalImpressionsAttribute(): int
    {
        return $this->results()->sum('impressions');
    }

    public function getTotalClicksAttribute(): int
    {
        return $this->results()->sum('clicks');
    }

    public function getTotalLeadsGeneratedAttribute(): int
    {
        return $this->results()->sum('leads_generated');
    }

    public function getTotalConversionsAttribute(): int
    {
        return $this->results()->sum('conversions');
    }

    public function getTotalCostAttribute(): float
    {
        return $this->results()->sum('cost');
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->results()->sum('revenue');
    }

    public function getClickThroughRateAttribute(): float
    {
        $impressions = $this->total_impressions;
        if ($impressions === 0) {
            return 0;
        }

        return ($this->total_clicks / $impressions) * 100;
    }

    public function getCostPerLeadAttribute(): float
    {
        $leadsGenerated = $this->total_leads_generated;
        if ($leadsGenerated === 0) {
            return 0;
        }

        return $this->total_cost / $leadsGenerated;
    }
}