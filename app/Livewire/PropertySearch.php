<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\PropertyType;
use Livewire\Component;
use Livewire\WithPagination;

class PropertySearch extends Component
{
    use WithPagination;
    
    public $search = '';
    public $minPrice = null;
    public $maxPrice = null;
    public $minBedrooms = null;
    public $minBathrooms = null;
    public $propertyType = '';
    public $city = '';
    public $status = 'available';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'minBedrooms' => ['except' => ''],
        'minBathrooms' => ['except' => ''],
        'propertyType' => ['except' => ''],
        'city' => ['except' => ''],
        'status' => ['except' => 'available'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->reset([
            'search', 'minPrice', 'maxPrice', 'minBedrooms', 
            'minBathrooms', 'propertyType', 'city', 'status'
        ]);
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function render()
    {
        $query = Property::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('reference', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        
        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }
        
        if ($this->minBedrooms) {
            $query->where('bedrooms', '>=', $this->minBedrooms);
        }
        
        if ($this->minBathrooms) {
            $query->where('bathrooms', '>=', $this->minBathrooms);
        }
        
        if ($this->propertyType) {
            $query->where('property_type_id', $this->propertyType);
        }
        
        if ($this->city) {
            $query->where('city', $this->city);
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $properties = $query->paginate(12);
        $propertyTypes = PropertyType::orderBy('name')->get();
        $cities = Property::select('city')->distinct()->orderBy('city')->pluck('city');
        
        return view('livewire.property-search', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
        ]);
    }
}