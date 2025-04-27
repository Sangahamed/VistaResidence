<?php

namespace App\Console\Commands;

use App\Models\SavedSearch;
use App\Models\Property;
use App\Notifications\NewPropertiesMatchingSearch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendSearchAlerts extends Command
{
    protected $signature = 'search:send-alerts {frequency?}';
    protected $description = 'Send alerts for saved searches based on frequency';

    public function handle()
    {
        $frequency = $this->argument('frequency');
        
        $query = SavedSearch::whereNotNull('alert_frequency');
        
        if ($frequency) {
            $query->where('alert_frequency', $frequency);
        }
        
        $savedSearches = $query->get();
        
        $this->info('Processing ' . $savedSearches->count() . ' saved searches');
        
        foreach ($savedSearches as $savedSearch) {
            if (!$savedSearch->shouldSendAlert()) {
                $this->line('Skipping search #' . $savedSearch->id . ' - Not due for alert');
                continue;
            }
            
            $this->line('Processing search #' . $savedSearch->id . ' - ' . $savedSearch->name);
            
            // Récupérer la date de la dernière alerte
            $lastAlertDate = $savedSearch->last_alert_sent_at ?? Carbon::now()->subDays(30);
            
            // Construire la requête de recherche
            $query = Property::query();
            
            // Ajouter la condition pour ne récupérer que les nouvelles propriétés
            $query->where('created_at', '>', $lastAlertDate);
            
            // Appliquer les critères de recherche
            $criteria = $savedSearch->criteria;
            
            // Filtrer par type de propriété
            if (!empty($criteria['type'])) {
                $query->where('type', $criteria['type']);
            }
            
            // Filtrer par type de transaction
            if (!empty($criteria['transaction_type'])) {
                $query->where('transaction_type', $criteria['transaction_type']);
            }
            
            // Filtrer par ville
            if (!empty($criteria['city'])) {
                $query->where('city', 'LIKE', '%' . $criteria['city'] . '%');
            }
            
            // Filtrer par code postal
            if (!empty($criteria['postal_code'])) {
                $query->where('postal_code', 'LIKE', '%' . $criteria['postal_code'] . '%');
            }
            
            // Filtrer par prix
            if (!empty($criteria['min_price'])) {
                $query->where('price', '>=', $criteria['min_price']);
            }
            if (!empty($criteria['max_price'])) {
                $query->where('price', '<=', $criteria['max_price']);
            }
            
            // Filtrer par surface
            if (!empty($criteria['min_surface'])) {
                $query->where('surface', '>=', $criteria['min_surface']);
            }
            if (!empty($criteria['max_surface'])) {
                $query->where('surface', '<=', $criteria['max_surface']);
            }
            
            // Filtrer par nombre de pièces
            if (!empty($criteria['min_rooms'])) {
                $query->where('rooms', '>=', $criteria['min_rooms']);
            }
            if (!empty($criteria['max_rooms'])) {
                $query->where('rooms', '<=', $criteria['max_rooms']);
            }
            
            // Filtrer par nombre de chambres
            if (!empty($criteria['min_bedrooms'])) {
                $query->where('bedrooms', '>=', $criteria['min_bedrooms']);
            }
            if (!empty($criteria['max_bedrooms'])) {
                $query->where('bedrooms', '<=', $criteria['max_bedrooms']);
            }
            
            // Filtrer par nombre de salles de bain
            if (!empty($criteria['min_bathrooms'])) {
                $query->where('bathrooms', '>=', $criteria['min_bathrooms']);
            }
            if (!empty($criteria['max_bathrooms'])) {
                $query->where('bathrooms', '<=', $criteria['max_bathrooms']);
            }
            
            // Filtrer par caractéristiques
            if (!empty($criteria['features'])) {
                foreach ($criteria['features'] as $feature) {
                    $query->whereJsonContains('features', $feature);
                }
            }
            
            // Recherche géographique par rayon
            if (!empty($criteria['latitude']) && !empty($criteria['longitude']) && !empty($criteria['radius'])) {
                $lat = $criteria['latitude'];
                $lng = $criteria['longitude'];
                $radius = $criteria['radius'];
                
                // Calcul de la distance en utilisant la formule de Haversine
                $query->selectRaw("*, 
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", 
                    [$lat, $lng, $lat])
                    ->having('distance', '<=', $radius);
            }
            
            // Exécuter la requête
            $properties = $query->get();
            
            $this->line('Found ' . $properties->count() . ' new properties');
            
            if ($properties->count() > 0) {
                // Envoyer la notification
                $savedSearch->user->notify(new NewPropertiesMatchingSearch($savedSearch, $properties));
                $this->info('Alert sent for search #' . $savedSearch->id);
            }
            
            // Marquer l'alerte comme envoyée
            $savedSearch->markAlertSent();
        }
        
        $this->info('All alerts processed successfully');
    }
}