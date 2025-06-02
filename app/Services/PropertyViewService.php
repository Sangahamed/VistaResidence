<?php

namespace App\Services;

use App\Models\PropertyView;
use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PropertyViewService
{
   public function recordPropertyView(
    int $propertyId, 
    ?int $userId = null, 
    ?string $sessionId = null,
    ?float $lat = null,
    ?float $lng = null
): PropertyView {

    $lat = is_numeric($lat) ? floatval($lat) : null;
    $lng = is_numeric($lng) ? floatval($lng) : null; 
    
    $record = PropertyView::updateOrCreate(
        [
            'property_id' => $propertyId,
            'user_id' => $userId,
            'session_id' => $sessionId
        ],
        [
            'view_count' => DB::raw('view_count + 1'),
            'interaction_score' => DB::raw('interaction_score + 1'),
            'last_viewed_at' => now()
        ]
    );

    // Mettre à jour le score viral de la propriété
    if ($userId) {
        $property = Property::find($propertyId);
        $property->viral_score = ($property->views_count * 0.7) + ($property->favorites_count * 0.3);
        $property->save();
    }

    return $record;
}
}