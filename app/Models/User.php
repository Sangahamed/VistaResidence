<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'picture',
        'address',
        'phone',
        'email_verified_at',
        'verified',
        'status',
        'provider',
        'provider_id',
        'last_login_at',
        'last_login_ip',
        'last_login_agent_user',
        'device_type',
        'device_os',
        'device_browser',
        'device_resolution',
        'device_language',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'failed_login_attempts',
        'last_failed_login_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_failed_login_at' => 'datetime',

        ];
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function getPictureAttribute($value){
        if( $value ){
            return asset('/images/users/'.$value);
        }else{
            return asset('/images/users/default-avatar.png');
        }
    }
}
