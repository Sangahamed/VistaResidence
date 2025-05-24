<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class ToggleFavorite extends Component
{
    public $property;
    public $isFavorite;

    public function mount($property)
    {
        $this->property = $property;
        $this->isFavorite = $property->favorites()
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function updateFavoriteState()
{
    $this->isFavorite = $this->property->favorites()->where('user_id', auth()->id())->exists();
}


    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isFavorite) {
            Favorite::where([
                'user_id' => Auth::id(),
                'property_id' => $this->property->id
            ])->delete();
            $this->dispatch('favoriteUpdated', propertyId: $this->property->id);
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'property_id' => $this->property->id
            ]);
            $this->dispatch('favoriteUpdated', propertyId: $this->property->id);
        }

        // Mettre à jour l'état du favori en temps réel
        $this->isFavorite = !$this->isFavorite;
    }

    public function render()
    {
        return view('livewire.toggle-favorite');
    }
}
