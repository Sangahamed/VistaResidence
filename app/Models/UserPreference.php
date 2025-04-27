<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_locations',
        'preferred_property_types',
        'min_price',
        'max_price',
        'min_bedrooms',
        'min_bathrooms',
        'min_surface',
        'has_garden',
        'has_balcony',
        'has_parking',
        'has_elevator',
        'preferred_amenities',
    ];

    protected $casts = [
        'preferred_locations' => 'array',
        'preferred_property_types' => 'array',
        'has_garden' => 'boolean',
        'has_balcony' => 'boolean',
        'has_parking' => 'boolean',
        'has_elevator' => 'boolean',
        'preferred_amenities' => 'array',
    ];

    /**
     * Get the user that owns the preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}