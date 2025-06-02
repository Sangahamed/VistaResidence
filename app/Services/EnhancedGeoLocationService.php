<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EnhancedGeoLocationService
{
    // Quartiers d'Abidjan et leurs coordonnées approximatives
    protected $abidjanDistricts = [
        'yopougon' => ['lat' => 5.3364, 'lng' => -4.0738],
        'plateau' => ['lat' => 5.3197, 'lng' => -4.0267],
        'cocody' => ['lat' => 5.3447, 'lng' => -3.9889],
        'adjame' => ['lat' => 5.3781, 'lng' => -4.0178],
        'treichville' => ['lat' => 5.2944, 'lng' => -4.0267],
        'marcory' => ['lat' => 5.2833, 'lng' => -4.0000],
        'koumassi' => ['lat' => 5.2833, 'lng' => -3.9667],
        'port-bouet' => ['lat' => 5.2500, 'lng' => -3.9167],
        'abobo' => ['lat' => 5.4167, 'lng' => -4.0167],
        'anyama' => ['lat' => 5.4833, 'lng' => -4.0500],
        'bingerville' => ['lat' => 5.3500, 'lng' => -3.8833],
        'songon' => ['lat' => 5.3333, 'lng' => -4.2500],
        'attécoubé' => ['lat' => 5.3333, 'lng' => -4.0833],
        'attecoube' => ['lat' => 5.3333, 'lng' => -4.0833],
    ];

    public function getLocationFromIP()
    {
        // Ne pas utiliser le cache pour forcer la détection du VPN
        $cacheKey = 'user_location_' . request()->ip() . '_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, 3600, function() {
            try {
                // Essayer plusieurs services dans l'ordre
                $location = $this->tryIpApi() ?? 
                           $this->tryIpInfo() ?? 
                           $this->tryIpGeolocation() ?? 
                           $this->tryIpStack() ??
                           $this->getDefaultLocation();
                
                Log::info('Detected location: ', $location);
                return $location;
                
            } catch (\Exception $e) {
                Log::error("GeoLocation error: " . $e->getMessage());
                return $this->getDefaultLocation();
            }
        });
    }

    private function tryIpApi()
    {
        try {
            $response = Http::timeout(5)->get('http://ip-api.com/json/' . request()->ip());
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'success') {
                    return [
                        'lat' => (float)$data['lat'],
                        'lng' => (float)$data['lon'],
                        'city' => $data['city'],
                        'country' => $data['country'],
                        'countryCode' => $data['countryCode'],
                        'source' => 'ip-api'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('IP-API failed: ' . $e->getMessage());
        }
        return null;
    }

    private function tryIpInfo()
    {
        try {
            $token = config('services.ipinfo.token');
            $url = $token ? "https://ipinfo.io/{request()->ip()}?token={$token}" : "https://ipinfo.io/{request()->ip()}";
            
            $response = Http::timeout(5)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['loc'])) {
                    [$lat, $lng] = explode(',', $data['loc']);
                    return [
                        'lat' => (float)$lat,
                        'lng' => (float)$lng,
                        'city' => $data['city'] ?? 'Unknown',
                        'country' => $data['country'] ?? 'Unknown',
                        'countryCode' => $data['country'] ?? 'Unknown',
                        'source' => 'ipinfo'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('IPInfo failed: ' . $e->getMessage());
        }
        return null;
    }

    private function tryIpGeolocation()
    {
        try {
            $response = Http::timeout(5)->get('https://api.ipgeolocation.io/ipgeo', [
                'apiKey' => config('services.ipgeolocation.key', ''),
                'ip' => request()->ip()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'lat' => (float)$data['latitude'],
                    'lng' => (float)$data['longitude'],
                    'city' => $data['city'],
                    'country' => $data['country_name'],
                    'countryCode' => $data['country_code2'],
                    'source' => 'ipgeolocation'
                ];
            }
        } catch (\Exception $e) {
            Log::warning('IPGeolocation failed: ' . $e->getMessage());
        }
        return null;
    }

    private function tryIpStack()
    {
        try {
            $response = Http::timeout(5)->get('http://api.ipstack.com/' . request()->ip(), [
                'access_key' => config('services.ipstack.key', ''),
                'format' => 1
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['latitude'])) {
                    return [
                        'lat' => (float)$data['latitude'],
                        'lng' => (float)$data['longitude'],
                        'city' => $data['city'],
                        'country' => $data['country_name'],
                        'countryCode' => $data['country_code'],
                        'source' => 'ipstack'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('IPStack failed: ' . $e->getMessage());
        }
        return null;
    }

    public function getDefaultLocation()
    {
        return [
            'lat' => 5.3600,
            'lng' => -4.0083,
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'countryCode' => 'CI',
            'source' => 'default'
        ];
    }

    /**
     * Normalise les noms de villes pour Abidjan
     */
    public function normalizeAbidjanDistrict($city)
    {
        $cityLower = strtolower(trim($city));
        
        // Si c'est un quartier d'Abidjan, retourner 'Abidjan'
        if (array_key_exists($cityLower, $this->abidjanDistricts)) {
            return 'Abidjan';
        }
        
        // Vérifications supplémentaires pour les variantes
        $abidjanVariants = [
            'abidjan', 'grand abidjan', 'district d\'abidjan',
            'yopougon', 'plateau', 'cocody', 'adjame', 'treichville',
            'marcory', 'koumassi', 'port-bouet', 'abobo', 'anyama',
            'bingerville', 'songon', 'attécoubé', 'attecoube'
        ];
        
        foreach ($abidjanVariants as $variant) {
            if (str_contains($cityLower, $variant)) {
                return 'Abidjan';
            }
        }
        
        return $city;
    }

    /**
     * Obtient les coordonnées d'un quartier d'Abidjan
     */
    public function getAbidjanDistrictCoordinates($district)
    {
        $districtLower = strtolower(trim($district));
        
        if (array_key_exists($districtLower, $this->abidjanDistricts)) {
            return $this->abidjanDistricts[$districtLower];
        }
        
        // Coordonnées par défaut d'Abidjan centre
        return ['lat' => 5.3600, 'lng' => -4.0083];
    }
}