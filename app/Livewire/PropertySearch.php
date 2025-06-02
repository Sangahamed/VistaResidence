<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PropertySearch extends Component
{
    public $search = '';
    public $type = '';
    public $city = '';
    public $priceMin = '';
    public $priceMax = '';
    public $isSearching = false;
    public $saveSearch = false;

    public $propertyTypes = [];
    public $propertyCities = [];
    public $minPrice = 0;
    public $maxPrice = 0;

    protected $listeners = ['positionUpdated'];

    public function mount()
    {
        // Utiliser le cache pour optimiser les performances
        $this->propertyTypes = Cache::remember('property_types', 3600, function () {
            return Property::distinct()->pluck('type')->toArray();
        });

        $this->propertyCities = Cache::remember('property_cities', 3600, function () {
            return Property::distinct()->pluck('city')->toArray();
        });

        $priceRange = Cache::remember('property_price_range', 3600, function () {
            return Property::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        });

        $this->minPrice = $priceRange->min_price ?? 20000;
        $this->maxPrice = $priceRange->max_price ?? 800000;

        // Valeurs par défaut ou depuis la requête
        $this->priceMin = request('price_min', $this->minPrice);
        $this->priceMax = request('price_max', $this->maxPrice);
        $this->search = request('search', '');
        $this->type = request('type', '');
        $this->city = request('city', '');
    }

    public function submitSearch()
    {
        $this->isSearching = true;

        $filtres = [
            'search' => $this->search,
            'type' => $this->type,
            'city' => $this->city,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ];

        // Sauvegarder la recherche si demandé
        if ($this->saveSearch && auth()->check()) {
            $this->saveUserSearch($filtres);
        }

        try {
            // Dispatch des événements Livewire
            $this->dispatch('filtersUpdated', $filtres);
            $this->dispatch('search-started');
        } catch (\Exception $e) {
            $this->addError('search', 'Erreur lors de la recherche');
            Log::error("Erreur recherche : " . $e->getMessage());
        } finally {
            $this->isSearching = false;
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'type', 'city']);
        $this->priceMin = $this->minPrice;
        $this->priceMax = $this->maxPrice;
        $this->dispatch('filtersUpdated', []);
    }

    protected function saveUserSearch($filters)
    {
        auth()->user()->savedSearches()->create([
            'criteria' => json_encode(array_filter($filters)),
            'name' => $this->generateSearchName($filters)
        ]);

        session()->flash('message', 'Recherche sauvegardée avec succès!');
    }

    protected function generateSearchName($filters)
    {
        $parts = [];
        
        if (!empty($filters['type'])) {
            $parts[] = ucfirst($filters['type']);
        }
        
        if (!empty($filters['city'])) {
            $parts[] = 'à ' . ucfirst($filters['city']);
        }
        
        if (!empty($filters['priceMin']) || !empty($filters['priceMax'])) {
            $priceRange = '';
            if (!empty($filters['priceMin'])) {
                $priceRange .= number_format($filters['priceMin']) . ' FCFA';
            }
            if (!empty($filters['priceMax'])) {
                $priceRange .= (!empty($filters['priceMin']) ? ' - ' : 'Jusqu\'à ') . number_format($filters['priceMax']) . ' FCFA';
            }
            $parts[] = $priceRange;
        }
        
        return !empty($parts) ? implode(' ', $parts) : 'Recherche du ' . now()->format('d/m/Y');
    }

    public function positionUpdated($position)
    {
        // Mettre à jour la recherche basée sur la nouvelle position
        if (empty($this->city) && isset($position['city'])) {
            $this->city = $position['city'];
        }
    }

    public function render()
    {
        return view('livewire.property-search');
    }
}