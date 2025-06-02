<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'name',
        'criteria'
    ];

    protected $casts = [
        'criteria' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $user_id, $session_id)
    {
        return $query->where(function($q) use ($user_id, $session_id) {
            $q->where('user_id', $user_id)
              ->orWhere('session_id', $session_id);
        });
    }

    public function convertToUser($user_id)
    {
        if ($this->user_id) return false;
        
        return $this->update([
            'user_id' => $user_id,
            'session_id' => null
        ]);
    }
}