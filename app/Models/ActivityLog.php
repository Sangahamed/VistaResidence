<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActivityLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'admin_id', 'action', 'details',
        'ip_address', 'user_agent', 'metadata',
        'is_suspicious', 'suspicion_type', 'risk_score',
        'analyzed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_suspicious' => 'boolean',
        'analyzed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
