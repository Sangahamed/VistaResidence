<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'new_property_alerts',
        'price_change_alerts',
        'status_change_alerts',
        'saved_search_alerts',
        'notification_frequency',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'new_property_alerts' => 'boolean',
        'price_change_alerts' => 'boolean',
        'status_change_alerts' => 'boolean',
        'saved_search_alerts' => 'boolean',
        'notification_frequency' => 'array',
    ];

    /**
     * Get the user that owns the notification preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}