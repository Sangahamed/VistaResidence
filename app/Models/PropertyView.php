<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'session_id',
        'view_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
    ];

    /**
     * Get the user that viewed the property.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property that was viewed.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}