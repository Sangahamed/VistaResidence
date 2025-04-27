<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointOfInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'latitude',
        'longitude',
        'address',
        'city',
        'postal_code',
    ];

    /**
     * Get the properties near this point of interest.
     */

     
    public function nearbyProperties($distance = 1) // distance in km
    {
        
        // Haversine formula to find properties within a certain distance
        $haversine = "(
            6371 * acos(
                cos(radians($this->latitude)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians($this->longitude)) 
                + sin(radians($this->latitude)) 
                * sin(radians(latitude))
            )
        )";

        return Property::selectRaw("*, $haversine AS distance")
            ->whereRaw("$haversine < ?", [$distance])
            ->orderBy('distance');
    }
}
