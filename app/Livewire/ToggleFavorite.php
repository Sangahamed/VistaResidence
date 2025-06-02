<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ToggleFavorite extends Component
{
    public $property;
    public $isFavorite = false;
    public $propertyId;

    public function mount($property)
    {
        $this->property = $property;
        $this->propertyId = $property->id;
        $this->updateFavoriteState();
    }

    public function updateFavoriteState()
    {
        if (Auth::check()) {
            $this->isFavorite = $this->property->favorites()
                ->where('user_id', Auth::id())
                ->exists();
        }
    }

    public function toggle()
    {
        try {
            if (!Auth::check()) {
                $this->dispatch('show-login-modal');
                return;
            }

            $userId = Auth::id();
            $propertyId = $this->propertyId;

            if ($this->isFavorite) {
                // Supprimer des favoris
                Favorite::where([
                    'user_id' => $userId,
                    'property_id' => $propertyId
                ])->delete();
                
                $this->isFavorite = false;
                $message = 'Retiré des favoris';
            } else {
                // Ajouter aux favoris
                Favorite::firstOrCreate([
                    'user_id' => $userId,
                    'property_id' => $propertyId
                ]);
                
                $this->isFavorite = true;
                $message = 'Ajouté aux favoris';
            }

            // Émettre l'événement avec le bon format
            $this->dispatch('favoriteUpdated', $propertyId);

            // Notification de succès (optionnel)
            session()->flash('message', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors du toggle favori: ' . $e->getMessage());
            session()->flash('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function render()
    {
        return view('livewire.toggle-favorite');
    }
}
