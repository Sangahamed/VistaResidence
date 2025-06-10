<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'owner_id',
        'processed_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
                
                // Vérifier l'unicité du slug
                $count = 1;
                $originalSlug = $company->slug;
                
                while (static::where('slug', $company->slug)->exists()) {
                    $company->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('job_title', 'is_admin', 'role_id')
            ->withTimestamps();
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
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

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function agencies()
    {
        return $this->hasMany(Agency::class);
    }

    public function activeModules()
    {
        return $this->belongsToMany(Module::class, 'company_module')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
