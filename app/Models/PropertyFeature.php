<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'icon', 'category', 'is_active'];

    /**
     * Get the properties that have this feature.
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_property_feature')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active features.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
