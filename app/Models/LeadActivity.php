<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'description',
        'scheduled_at',
        'is_completed',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
            ->where('is_completed', false)
            ->orderBy('scheduled_at');
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_at', '<', now())
            ->where('is_completed', false)
            ->orderBy('scheduled_at');
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true)
            ->orderBy('updated_at', 'desc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}