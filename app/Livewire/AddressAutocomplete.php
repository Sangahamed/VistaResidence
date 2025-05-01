<?php

namespace App\Livewire;

use Livewire\Component;

class AddressAutocomplete extends Component
{
    public $address = '';
    public $street = '';
    public $city = '';
    public $postalCode = '';
    public $country = '';
    public $latitude = null;
    public $longitude = null;
    
    public $showMap = true;
    public $mapZoom = 15;
    
    protected $listeners = ['addressSelected'];
    
    public function render()
    {
        return view('livewire.address-autocomplete');
    }
    
    public function addressSelected($data)
    {
        $this->address = $data['formatted_address'] ?? '';
        $this->street = $data['street'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->postalCode = $data['postal_code'] ?? '';
        $this->country = $data['country'] ?? '';
        $this->latitude = $data['latitude'] ?? null;
        $this->longitude = $data['longitude'] ?? null;
        
        $this->emit('addressUpdated', [
            'address' => $this->address,
            'street' => $this->street,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);
    }
    
    public function resetAddress()
    {
        $this->address = '';
        $this->street = '';
        $this->city = '';
        $this->postalCode = '';
        $this->country = '';
        $this->latitude = null;
        $this->longitude = null;
        
        $this->emit('addressReset');
    }
}