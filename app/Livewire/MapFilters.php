<?php

namespace App\Livewire;

use Livewire\Component;

class MapFilters extends Component
{
    public $propertyType;
    public $minPrice;
    public $maxPrice;

    public function updated()
    {
        $this->dispatch('updateFilters', [
            'type' => $this->propertyType,
            'price_min' => $this->minPrice,
            'price_max' => $this->maxPrice
        ])->to(MapManager::class);
    }

    public function render()
    {
        return view('livewire.map-filters');
    }
}
