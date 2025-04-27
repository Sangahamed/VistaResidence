<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_campaign_id',
        'date',
        'impressions',
        'clicks',
        'leads_generated',
        'conversions',
        'cost',
        'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'leads_generated' => 'integer',
        'conversions' => 'integer',
        'cost' => 'decimal:2',
        'revenue' => 'decimal:2',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'marketing_campaign_id');
    }

    public function getClickThroughRateAttribute(): float
    {
        if ($this->impressions === 0) {
            return 0;
        }

        return ($this->clicks / $this->impressions) * 100;
    }

    public function getConversionRateAttribute(): float
    {
        if ($this->leads_generated === 0) {
            return 0;
        }

        return ($this->conversions / $this->leads_generated) * 100;
    }

    public function getRoiAttribute(): float
    {
        if ($this->cost == 0) {
            return 0;
        }

        return (($this->revenue - $this->cost) / $this->cost) * 100;
    }

    public function getCostPerLeadAttribute(): float
    {
        if ($this->leads_generated === 0) {
            return 0;
        }

        return $this->cost / $this->leads_generated;
    }
}