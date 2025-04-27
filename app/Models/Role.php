<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_default'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('slug', $permission);
        }
        
        return $permission->intersect($this->permissions)->count() > 0;
    }

    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        
        $this->permissions()->syncWithoutDetaching($permission);
        
        return $this;
    }

    public function withdrawPermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        
        $this->permissions()->detach($permission);
        
        return $this;
    }

    public function syncPermissions($permissions)
    {
        if (is_array($permissions)) {
            $permissions = Permission::whereIn('slug', $permissions)->get();
        }
        
        $this->permissions()->sync($permissions);
        
        return $this;
    }
}
