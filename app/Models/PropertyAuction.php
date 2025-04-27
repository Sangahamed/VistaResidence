<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PropertyAuction extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'starting_price',
        'reserve_price',
        'current_bid',
        'current_bidder_id',
        'total_bids',
        'start_date',
        'end_date',
        'status',
        'bid_increment',
        'auto_extend',
        'auto_extend_minutes',
        'terms_conditions',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'auto_extend' => 'boolean',
    ];

    /**
     * Get the property that is being auctioned.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the current highest bidder.
     */
    public function currentBidder()
    {
        return $this->belongsTo(User::class, 'current_bidder_id');
    }

    /**
     * Get all bids for this auction.
     */
    public function bids()
    {
        return $this->hasMany(AuctionBid::class);
    }

    /**
     * Get the minimum next bid amount.
     */
    public function getMinimumBidAmount()
    {
        return $this->current_bid 
            ? $this->current_bid + $this->bid_increment 
            : $this->starting_price;
    }

    /**
     * Check if the auction is active.
     */
    public function isActive()
    {
        $now = Carbon::now();
        return $this->status === 'active' && 
               $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThan($this->end_date);
    }

    /**
     * Check if the auction has ended.
     */
    public function hasEnded()
    {
        return $this->status === 'ended' || Carbon::now()->greaterThanOrEqualTo($this->end_date);
    }

    /**
     * Check if the auction is upcoming.
     */
    public function isUpcoming()
    {
        return $this->status === 'upcoming' && Carbon::now()->lessThan($this->start_date);
    }

    /**
     * Check if the reserve price has been met.
     */
    public function isReserveMet()
    {
        if (!$this->reserve_price) {
            return true;
        }
        
        return $this->current_bid >= $this->reserve_price;
    }

    /**
     * Place a bid on the auction.
     */
    public function placeBid(User $user, $amount, $isAutoBid = false, $maxAutoBidAmount = null)
    {
        // Check if auction is active
        if (!$this->isActive()) {
            throw new \Exception('Cette enchère n\'est pas active.');
        }
        
        // Check if bid amount is valid
        if ($amount < $this->getMinimumBidAmount()) {
            throw new \Exception('Le montant de l\'enchère doit être supérieur à ' . $this->getMinimumBidAmount() . ' €.');
        }
        
        // Check if user is outbidding themselves
        if ($this->current_bidder_id === $user->id && $amount <= $this->current_bid) {
            throw new \Exception('Vous êtes déjà le meilleur enchérisseur.');
        }
        
        // Create the bid
        $bid = new AuctionBid([
            'user_id' => $user->id,
            'amount' => $amount,
            'is_auto_bid' => $isAutoBid,
            'max_auto_bid_amount' => $maxAutoBidAmount,
        ]);
        
        $this->bids()->save($bid);
        
        // Update auction
        $this->current_bid = $amount;
        $this->current_bidder_id = $user->id;
        $this->total_bids += 1;
        
        // Mark previous winning bid as not winning
        $this->bids()->where('is_winning', true)->update(['is_winning' => false]);
        
        // Mark this bid as winning
        $bid->update(['is_winning' => true]);
        
        // Extend auction time if needed
        if ($this->auto_extend && $this->end_date->diffInMinutes(Carbon::now()) < $this->auto_extend_minutes) {
            $this->end_date = $this->end_date->addMinutes($this->auto_extend_minutes);
        }
        
        $this->save();
        
        return $bid;
    }

    /**
     * Process auto bids for this auction.
     */
    public function processAutoBids()
    {
        // Get all active auto bids for this auction, excluding the current winner
        $autoBids = $this->bids()
            ->where('is_auto_bid', true)
            ->where('user_id', '!=', $this->current_bidder_id)
            ->whereRaw('max_auto_bid_amount > ?', [$this->current_bid])
            ->orderBy('max_auto_bid_amount', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        if ($autoBids->isEmpty()) {
            return;
        }
        
        // Get the highest auto bid
        $highestAutoBid = $autoBids->first();
        $highestAutoBidder = User::find($highestAutoBid->user_id);
        
        // Calculate the bid amount
        $bidAmount = min(
            $highestAutoBid->max_auto_bid_amount,
            $this->current_bid + $this->bid_increment
        );
        
        // Place the bid
        $this->placeBid($highestAutoBidder, $bidAmount, true, $highestAutoBid->max_auto_bid_amount);
        
        // Check if there are more auto bids to process
        if ($autoBids->count() > 1) {
            $this->processAutoBids();
        }
    }

    /**
     * End the auction.
     */
    public function endAuction()
    {
        if ($this->status !== 'ended') {
            $this->status = 'ended';
            $this->save();
            
            // Notify the winner if reserve is met
            if ($this->current_bidder_id && $this->isReserveMet()) {
                $winner = $this->currentBidder;
                // Send notification to winner
            }
            
            // Notify the property owner
            $owner = $this->property->user;
            // Send notification to owner
        }
    }

    /**
     * Scope a query to only include active auctions.
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now);
    }

    /**
     * Scope a query to only include upcoming auctions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('start_date', '>', Carbon::now());
    }

    /**
     * Scope a query to only include ended auctions.
     */
    public function scopeEnded($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'ended')
              ->orWhere('end_date', '<=', Carbon::now());
        });
    }
}
