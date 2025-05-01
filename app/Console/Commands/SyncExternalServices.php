<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\ExternalSync;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncExternalServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-external-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise les propriétés avec des services externes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Synchronisation avec des services externes...');
        
        // Liste des services externes configurés
        $services = config('external_services', []);
        
        foreach ($services as $service => $config) {
            if ($config['enabled']) {
                $this->syncService($service, $config);
            }
        }
        
        $this->info('Synchronisation avec des services externes terminée.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Synchronise les propriétés avec un service externe.
     */
    private function syncService($service, $config)
    {
        $this->info("Synchronisation avec le service : {$service}");
        
        // Récupérer les propriétés à synchroniser
        $properties = $this->getPropertiesToSync($service);
        
        $this->info("Nombre de propriétés à synchroniser : " . $properties->count());
        
        foreach ($properties as $property) {
            try {
                // Préparer les données de la propriété pour le service externe
                $data = $this->preparePropertyData($property, $config);
                
                // Vérifier si la propriété existe déjà sur le service externe
                $externalSync = ExternalSync::where('property_id', $property->id)
                    ->where('service', $service)
                    ->first();
                
                if ($externalSync) {
                    // Mettre à jour la propriété sur le service externe
                    $this->updateExternalProperty($service, $config, $externalSync->external_id, $data);
                    $externalSync->last_sync = Carbon::now();
                    $externalSync->save();
                    
                    $this->info("Propriété mise à jour sur {$service} : {$property->title} (ID: {$property->id})");
                } else {
                    // Créer la propriété sur le service externe
                    $externalId = $this->createExternalProperty($service, $config, $data);
                    
                    if ($externalId) {
                        ExternalSync::create([
                            'property_id' => $property->id,
                            'service' => $service,
                            'external_id' => $externalId,
                            'last_sync' => Carbon::now(),
                        ]);
                        
                        $this->info("Propriété créée sur {$service} : {$property->title} (ID: {$property->id}, External ID: {$externalId})");
                    }
                }
            } catch (\Exception $e) {
                $this->error("Erreur lors de la synchronisation de la propriété {$property->id} avec {$service} : " . $e->getMessage());
                Log::error("Erreur de synchronisation externe", [
                    'service' => $service,
                    'property_id' => $property->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        // Vérifier les propriétés qui ont été supprimées ou désactivées
        $this->checkRemovedProperties($service);
    }
    
    /**
     * Récupère les propriétés à synchroniser avec un service externe.
     */
    private function getPropertiesToSync($service)
    {
        // Récupérer les propriétés publiées qui n'ont pas été synchronisées récemment
        return Property::where('status', 'published')
            ->where(function ($query) use ($service) {
                $query->whereDoesntHave('externalSyncs', function ($q) use ($service) {
                    $q->where('service', $service);
                })
                ->orWhereHas('externalSyncs', function ($q) use ($service) {
                    $q->where('service', $service)
                      ->where('last_sync', '<', Carbon::now()->subHours(24));
                });
            })
            ->get();
    }
    
    /**
     * Prépare les données de la propriété pour le service externe.
     */
    private function preparePropertyData($property, $config)
    {
        // Cette méthode peut être personnalisée en fonction du format attendu par le service externe
        $data = [
            'title' => $property->title,
            'description' => $property->description,
            'price' => $property->price,
            'address' => $property->address,
            'city' => $property->city,
            'postal_code' => $property->postal_code,
            'country' => $property->country,
            'property_type' => $property->propertyType ? $property->propertyType->name : null,
            'transaction_type' => $property->transaction_type,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'area' => $property->area,
            'features' => $property->propertyFeatures->pluck('name')->toArray(),
            'images' => $property->getMediaUrls(),
            'contact' => [
                'name' => $property->agent ? $property->agent->user->name : ($property->agency ? $property->agency->name : null),
                'email' => $property->agent ? $property->agent->user->email : ($property->agency ? $property->agency->email : null),
                'phone' => $property->agent ? $property->agent->phone : ($property->agency ? $property->agency->phone : null),
            ],
        ];
        
        // Mapper les champs selon la configuration du service
        if (isset($config['field_mapping'])) {
            $mappedData = [];
            foreach ($config['field_mapping'] as $ourField => $externalField) {
                if (isset($data[$ourField])) {
                    $mappedData[$externalField] = $data[$ourField];
                }
            }
            return $mappedData;
        }
        
        return $data;
    }
    
    /**
     * Crée une propriété sur un service externe.
     */
    private function createExternalProperty($service, $config, $data)
    {
        try {
            $response = Http::withHeaders($config['headers'])
                ->post($config['endpoints']['create'], $data);
                
            if ($response->successful()) {
                $responseData = $response->json();
                return $responseData['id'] ?? null;
            } else {
                $this->error("Erreur lors de la création sur {$service} : " . $response->body());
                Log::error("Erreur de création sur service externe", [
                    'service' => $service,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            $this->error("Exception lors de la création sur {$service} : " . $e->getMessage());
            Log::error("Exception lors de la création sur service externe", [
                'service' => $service,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
    
    /**
     * Met à jour une propriété sur un service externe.
     */
    private function updateExternalProperty($service, $config, $externalId, $data)
    {
        try {
            $endpoint = str_replace('{id}', $externalId, $config['endpoints']['update']);
            
            $response = Http::withHeaders($config['headers'])
                ->put($endpoint, $data);
                
            if (!$response->successful()) {
                $this->error("Erreur lors de la mise à jour sur {$service} : " . $response->body());
                Log::error("Erreur de mise à jour sur service externe", [
                    'service' => $service,
                    'external_id' => $externalId,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            $this->error("Exception lors de la mise à jour sur {$service} : " . $e->getMessage());
            Log::error("Exception lors de la mise à jour sur service externe", [
                'service' => $service,
                'external_id' => $externalId,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Vérifie les propriétés qui ont été supprimées ou désactivées.
     */
    private function checkRemovedProperties($service)
    {
        $this->info("Vérification des propriétés supprimées pour le service : {$service}");
        
        // Récupérer les propriétés qui ne sont plus publiées mais qui sont toujours sur le service externe
        $syncs = ExternalSync::where('service', $service)
            ->whereHas('property', function ($query) {
                $query->where('status', '!=', 'published');
            })
            ->get();
            
        $this->info("Nombre de propriétés à supprimer : " . $syncs->count());
        
        $config = config("external_services.{$service}");
        
        foreach ($syncs as $sync) {
            try {
                $endpoint = str_replace('{id}', $sync->external_id, $config['endpoints']['delete']);
                
                $response = Http::withHeaders($config['headers'])
                    ->delete($endpoint);
                    
                if ($response->successful()) {
                    $sync->delete();
                    $this->info("Propriété supprimée sur {$service} : External ID: {$sync->external_id}");
                } else {
                    $this->error("Erreur lors de la suppression sur {$service} : " . $response->body());
                    Log::error("Erreur de suppression sur service externe", [
                        'service' => $service,
                        'external_id' => $sync->external_id,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Exception lors de la suppression sur {$service} : " . $e->getMessage());
                Log::error("Exception lors de la suppression sur service externe", [
                    'service' => $service,
                    'external_id' => $sync->external_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}