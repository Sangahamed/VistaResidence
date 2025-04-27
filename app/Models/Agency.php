<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'website', 'email', 
        'phone_number', 'address', 'city', 'zip_code', 'country', 
        'company_id', 'owner_id'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($agency) {
            if (empty($agency->slug)) {
                $agency->slug = Str::slug($agency->name);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agents()
    {
        return $this->belongsToMany(User::class, 'agency_agent')->withPivot(['role', 'commission_rate', 'bio', 'specialties']);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
