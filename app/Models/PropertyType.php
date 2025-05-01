<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'icon', 'is_active'];


    protected $casts = [
        'is_active' => 'boolean',
    ];

    
    /**
     * Get the properties that belong to this type.
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Scope a query to only include active property types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
