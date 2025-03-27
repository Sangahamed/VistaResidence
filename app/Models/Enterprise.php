<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Enterprise extends Model
{
    use HasRoles;

    protected $fillable = ['name', 'admin_id'];

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function members() {
        return $this->belongsToMany(User::class, 'enterprise_user')
                   ->withPivot('role_id');
    }
}