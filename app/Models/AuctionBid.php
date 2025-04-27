<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_auction_id',
        'user_id',
        'amount',
        'is_auto_bid',
        'max_auto_bid_amount',
        'is_winning',
    ];

    protected $casts = [
        'is_auto_bid' => 'boolean',
        'is_winning' => 'boolean',
    ];

    /**
     * Get the auction that owns the bid.
     */
    public function auction()
    {
        return $this->belongsTo(PropertyAuction::class, 'property_auction_id');
    }

    /**
     * Get the user that made the bid.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}