<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'type',
        'categories',
        'description',
        'photo',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',
        'rooms',
        'bathrooms',
        'bedrooms',
        'floors',
        'area',
        'price',
        'period',
        'features',
        'usage',
        'status',
        'owner_id',
    ];

    protected $casts = [
        'features' => 'array',

    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
