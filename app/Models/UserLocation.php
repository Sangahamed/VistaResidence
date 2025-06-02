<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserLocation extends Model
{
     use HasFactory;

    protected $fillable = ['user_id', 'lat', 'lng', 'accuracy', 'source']; // Ajout de user_id
}
