<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agency_id', 'bio', 'license_number', 'years_experience',
        'specialties', 'social_media', 'profile_image', 'status'
    ];

    protected $casts = [
        'specialties' => 'array',
        'social_media' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'agent_client', 'agent_id', 'client_id')
            ->withTimestamps();
    }
}
