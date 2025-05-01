<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email_new_property',
        'email_property_status',
        'email_new_lead',
        'email_lead_assigned',
        'email_visit_requested',
        'email_visit_status',
        'push_new_property',
        'push_property_status',
        'push_new_lead',
        'push_lead_assigned',
        'push_visit_requested',
        'push_visit_status',
        'sms_new_property',
        'sms_property_status',
        'sms_new_lead',
        'sms_lead_assigned',
        'sms_visit_requested',
        'sms_visit_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_new_property' => 'boolean',
        'email_property_status' => 'boolean',
        'email_new_lead' => 'boolean',
        'email_lead_assigned' => 'boolean',
        'email_visit_requested' => 'boolean',
        'email_visit_status' => 'boolean',
        'push_new_property' => 'boolean',
        'push_property_status' => 'boolean',
        'push_new_lead' => 'boolean',
        'push_lead_assigned' => 'boolean',
        'push_visit_requested' => 'boolean',
        'push_visit_status' => 'boolean',
        'sms_new_property' => 'boolean',
        'sms_property_status' => 'boolean',
        'sms_new_lead' => 'boolean',
        'sms_lead_assigned' => 'boolean',
        'sms_visit_requested' => 'boolean',
        'sms_visit_status' => 'boolean',
    ];

    /**
     * Get the user that owns the notification preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}