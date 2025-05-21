<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
        'frequency',
        'preferences'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'preferences' => 'array'
    ];

    protected $attributes = [
        'preferences' => '{
            "properties": {
                "new": true,
                "updated": true,
                "price_change": true,
                "status_change": true
            },
            "visits": {
                 "requested": true,
                "confirmed": true,
                "cancelled": true,
                "rescheduled": true,
                "reminder_24h": true,
                "reminder_1h": true
            },
            "favorites": {
                "price_drop": true,
                "status_change": true
            },
            "searches": {
                "new_matches": true
            }
        }'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldNotify($category, $type)
    {
        return $this->preferences[$category][$type] ?? false;
    }

    public function getNotificationChannels()
    {
        $channels = [];
        
        if ($this->email_enabled) $channels[] = 'mail';
        if ($this->push_enabled) $channels[] = 'database';
        if ($this->sms_enabled) $channels[] = 'sms';
        
        return $channels;
    }
}