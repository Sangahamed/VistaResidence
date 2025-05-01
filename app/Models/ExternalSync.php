<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSync extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'service',
        'external_id',
        'last_sync',
        'sync_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_sync' => 'datetime',
        'sync_data' => 'array',
    ];

    /**
     * Get the property that owns the external sync.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}