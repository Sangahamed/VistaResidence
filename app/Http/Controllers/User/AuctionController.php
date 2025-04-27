<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyAuction;
use App\Models\AuctionBid;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of the auctions.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'active');
        
        switch ($status) {
            case 'active':
                $auctions = PropertyAuction::with(['property', 'currentBidder'])
                    ->active()
                    ->latest('end_date')
                    ->paginate(12);
                break;
            case 'upcoming':
                $auctions = PropertyAuction::with(['property'])
                    ->upcoming()
                    ->latest('start_date')
                    ->paginate(12);
                break;
            case 'ended':
                $auctions = PropertyAuction::with(['property', 'currentBidder'])
                    ->ended()
                    ->latest('end_date')
                    ->paginate(12);
                break;
            default:
                $auctions = PropertyAuction::with(['property', 'currentBidder'])
                    ->latest('created_at')
                    ->paginate(12);
        }
        
        return view('auctions.index', compact('auctions', 'status'));
    }

    /**
     * Display the specified auction.
     */
    public function show(PropertyAuction $auction)
    {
        $auction->load(['property', 'property.user', 'currentBidder']);
        
        // Check if auction has ended but status is not updated
        if ($auction->status !== 'ended' && Carbon::now()->greaterThanOrEqualTo($auction->end_date)) {
            $auction->endAuction();
        }
        
        // Get recent bids
        $recentBids = $auction->bids()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Get user's bids if authenticated
        $userBids = auth()->check() 
            ? $auction->bids()->where('user_id', auth()->id())->latest()->get() 
            : collect();
        
        // Check if user has auto bid
        $userAutoBid = auth()->check() 
            ? $auction->bids()->where('user_id', auth()->id())->where('is_auto_bid', true)->latest()->first() 
            : null;
        
        return view('auctions.show', compact('auction', 'recentBids', 'userBids', 'userAutoBid'));
    }

    /**
     * Show the form for creating a new auction.
     */
    public function create(Property $property)
    {
        // Check if user is the owner of the property
        if (auth()->id() !== $property->user_id) {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Vous n\'êtes pas autorisé à créer une enchère pour cette propriété.');
        }
        
        // Check if property already has an active auction
        $existingAuction = PropertyAuction::where('property_id', $property->id)
            ->whereIn('status', ['upcoming', 'active'])
            ->first();
            
        if ($existingAuction) {
            return redirect()->route('auctions.show', $existingAuction)
                ->with('error', 'Cette propriété a déjà une enchère active ou à venir.');
        }
        
        return view('auctions.create', compact('property'));
    }

    /**
     * Store a newly created auction in storage.
     */
    public function store(Request $request, Property $property)
    {
        // Check if user is the owner of the property
        if (auth()->id() !== $property->user_id) {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Vous n\'êtes pas autorisé à créer une enchère pour cette propriété.');
        }
        
        $validated = $request->validate([
            'starting_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|min:1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'bid_increment' => 'required|numeric|min:1',
            'auto_extend' => 'boolean',
            'auto_extend_minutes' => 'required_if:auto_extend,1|numeric|min:1',
            'terms_conditions' => 'nullable|string',
        ]);
        
        // Create the auction
        $auction = new PropertyAuction([
            'property_id' => $property->id,
            'starting_price' => $validated['starting_price'],
            'reserve_price' => $validated['reserve_price'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'bid_increment' => $validated['bid_increment'],
            'auto_extend' => $request->boolean('auto_extend'),
            'auto_extend_minutes' => $validated['auto_extend_minutes'] ?? 10,
            'terms_conditions' => $validated['terms_conditions'],
            'status' => Carbon::parse($validated['start_date'])->isPast() ? 'active' : 'upcoming',
        ]);
        
        $auction->save();
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'L\'enchère a été créée avec succès.');
    }

    /**
     * Place a bid on an auction.
     */
    public function placeBid(Request $request, PropertyAuction $auction)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour enchérir.');
        }
        
        // Check if auction is active
        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cette enchère n\'est pas active.');
        }
        
        // Check if user is the property owner
        if (auth()->id() === $auction->property->user_id) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Vous ne pouvez pas enchérir sur votre propre propriété.');
        }
        
        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:' . $auction->getMinimumBidAmount(),
            'is_auto_bid' => 'boolean',
            'max_auto_bid_amount' => 'required_if:is_auto_bid,1|numeric|min:' . $auction->getMinimumBidAmount(),
        ]);
        
        try {
            // Place the bid
            $bid = $auction->placeBid(
                auth()->user(),
                $validated['bid_amount'],
                $request->boolean('is_auto_bid'),
                $request->input('max_auto_bid_amount')
            );
            
            // Process auto bids
            if ($auction->bids()->where('is_auto_bid', true)->where('user_id', '!=', auth()->id())->exists()) {
                $auction->processAutoBids();
            }
            
            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Votre enchère a été placée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update auto bid settings.
     */
    public function updateAutoBid(Request $request, PropertyAuction $auction)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour modifier votre enchère automatique.');
        }
        
        // Check if auction is active
        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cette enchère n\'est pas active.');
        }
        
        $validated = $request->validate([
            'max_auto_bid_amount' => 'required|numeric|min:' . $auction->getMinimumBidAmount(),
        ]);
        
        // Get user's auto bid
        $autoBid = $auction->bids()
            ->where('user_id', auth()->id())
            ->where('is_auto_bid', true)
            ->latest()
            ->first();
        
        if ($autoBid) {
            // Update auto bid
            $autoBid->update([
                'max_auto_bid_amount' => $validated['max_auto_bid_amount'],
            ]);
            
            // Process auto bids if needed
            if ($auction->current_bidder_id !== auth()->id() && $validated['max_auto_bid_amount'] > $auction->current_bid) {
                // Place a new bid
                $auction->placeBid(
                    auth()->user(),
                    min($validated['max_auto_bid_amount'], $auction->current_bid + $auction->bid_increment),
                    true,
                    $validated['max_auto_bid_amount']
                );
                
                // Process other auto bids
                $auction->processAutoBids();
            }
            
            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Votre enchère automatique a été mise à jour avec succès.');
        } else {
            // Create new auto bid
            try {
                $auction->placeBid(
                    auth()->user(),
                    $auction->getMinimumBidAmount(),
                    true,
                    $validated['max_auto_bid_amount']
                );
                
                // Process auto bids
                $auction->processAutoBids();
                
                return redirect()->route('auctions.show', $auction)
                    ->with('success', 'Votre enchère automatique a été créée avec succès.');
            } catch (\Exception $e) {
                return redirect()->route('auctions.show', $auction)
                    ->with('error', $e->getMessage());
            }
        }
    }

    /**
     * Cancel auto bid.
     */
    public function cancelAutoBid(PropertyAuction $auction)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour annuler votre enchère automatique.');
        }
        
        // Check if auction is active
        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cette enchère n\'est pas active.');
        }
        
        // Get user's auto bid
        $autoBid = $auction->bids()
            ->where('user_id', auth()->id())
            ->where('is_auto_bid', true)
            ->latest()
            ->first();
        
        if ($autoBid) {
            // Update auto bid
            $autoBid->update([
                'is_auto_bid' => false,
                'max_auto_bid_amount' => null,
            ]);
            
            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Votre enchère automatique a été annulée avec succès.');
        } else {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Vous n\'avez pas d\'enchère automatique active.');
        }
    }

    /**
     * Show user's auction history.
     */
    public function userHistory()
    {
        // Get user's bids
        $bids = AuctionBid::with(['auction', 'auction.property'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        // Get auctions where user is the current highest bidder
        $winningAuctions = PropertyAuction::with('property')
            ->where('current_bidder_id', auth()->id())
            ->latest('end_date')
            ->get();
        
        return view('auctions.history', compact('bids', 'winningAuctions'));
    }

    /**
     * Cancel an auction (for property owner).
     */
    public function cancel(PropertyAuction $auction)
    {
        // Check if user is the property owner
        if (auth()->id() !== $auction->property->user_id) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Vous n\'êtes pas autorisé à annuler cette enchère.');
        }
        
        // Check if auction can be cancelled
        if ($auction->status === 'ended') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cette enchère est déjà terminée.');
        }
        
        if ($auction->status === 'active' && $auction->total_bids > 0) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Vous ne pouvez pas annuler une enchère active avec des offres.');
        }
        
        // Cancel the auction
        $auction->update(['status' => 'cancelled']);
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'L\'enchère a été annulée avec succès.');
    }
}
