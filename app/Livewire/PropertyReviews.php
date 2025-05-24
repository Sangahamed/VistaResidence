<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyReviews extends Component
{
    public Property $property;
    public int $rating = 0;
    public string $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:5|max:500',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function submitReview(): void
    {
        $this->validate();

        if ($this->property->reviews()->where('user_id', Auth::id())->exists()) {
            session()->flash('error', 'Vous avez déjà laissé un avis pour cette propriété.');
            return;
        }

        Review::create([
            'property_id' => $this->property->id,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);

        session()->flash('message', 'Merci pour votre avis !');
        $this->dispatch('reviewSubmitted'); // utile si tu veux rafraîchir d’autres composants
    }

    public function render()
    {
        $reviews = $this->property->reviews()->with('user')->latest()->get();
        $totalReviews = $reviews->count();
        $averageRating = round($reviews->avg('rating') ?? 0, 1);
        $hasReviewed = Auth::check() && $this->property->reviews()->where('user_id', Auth::id())->exists();

        return view('livewire.property-reviews', [
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'hasReviewed' => $hasReviewed,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);
    }
}
