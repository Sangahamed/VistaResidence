<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'website', 'email', 
        'phone', 'address', 'city', 'state', 'zip_code', 'country', 'owner_id'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('job_title', 'is_admin')
            ->withTimestamps();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    } 
    
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)
            ->withPivot('is_enabled', 'settings', 'expires_at')
            ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function admins()
    {
        return $this->users()->wherePivot('is_admin', true);
    }

    public function hasModule($module)
    {
        if (is_string($module)) {
            return $this->modules()->where('slug', $module)->wherePivot('is_enabled', true)->exists();
        }
        
        return $this->modules()->where('id', $module->id)->wherePivot('is_enabled', true)->exists();
    }
}
