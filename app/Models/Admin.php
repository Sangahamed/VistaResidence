<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = "admin";

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'picture',
        'last_login_at',
        'last_login_ip',
        'last_login_agent_user',
        'device_type',
        'device_os',
        'device_browser',
        'device_resolution',
        'device_language',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'failed_login_attempts',
        'last_failed_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
