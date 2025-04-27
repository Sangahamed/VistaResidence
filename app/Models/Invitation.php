<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'role_id',
        'company_id',
        'invited_by',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function generateToken()
    {
        return Str::random(32);
    }

    public function hasExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isDeclined()
    {
        return $this->status === 'declined';
    }
}