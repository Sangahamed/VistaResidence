<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'version', 'is_enabled', 'is_core', 'settings'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_core' => 'boolean',
        'settings' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($module) {
            if (empty($module->slug)) {
                $module->slug = Str::slug($module->name);
            }
        });
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('is_enabled', 'settings', 'expires_at')
            ->withTimestamps();
    }
}
