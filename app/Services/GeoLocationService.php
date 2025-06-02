<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Collection;

class GeoLocationService
{
    public function getNearbyProperties(float $lat, float $lng, int $radius = 10): Collection
    {
        return Property::selectRaw("*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    public function calculateDistanceSort($query, $lat, $lng)
    {
        return $query->orderByRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude))))", 
            [$lat, $lng, $lat]
        );
    }

    public function getLocationFromIP()
    {
        try {
            // Essayer plusieurs services
            $location = $this->tryIpApi() ?? $this->tryIpInfo() ?? $this->tryGeolocationDb();
            
            if (!$location) {
                return $this->getDefaultLocation();
            }

            if (app()->environment('local')) {
        return [
            'lat' => 5.3543,
            'lng' => -4.0016,
            'city' => 'Abidjan',
            'country' => 'CI',
            'source' => 'dev'
        ];
    }
            
            return $location;
        } catch (\Exception $e) {
            Log::error("GeoLocation error: " . $e->getMessage());
            return $this->getDefaultLocation();
        }
    }

    private function tryIpApi()
    {
        $response = Http::get('http://ip-api.com/json');
        
        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === 'success') {
                return [
                    'lat' => $data['lat'],
                    'lng' => $data['lon'],
                    'city' => $data['city'],
                    'country' => $data['countryCode'],
                    'source' => 'ip-api'
                ];
            }
        }
        return null;
    }

    private function tryIpInfo()
    {
        $response = Http::withToken(config('services.ipinfo.token'))->get('https://ipinfo.io');
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['loc'])) {
                [$lat, $lng] = explode(',', $data['loc']);
                return [
                    'lat' => $lat,
                    'lng' => $lng,
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'source' => 'ipinfo'
                ];
            }
        }
        return null;
    }

    private function tryGeolocationDb()
    {
        $response = Http::get('https://geolocation-db.com/jsonp');
        
        if ($response->successful()) {
            $json = preg_replace('/^callback\(/', '', $response->body());
            $json = preg_replace('/\);$/', '', $json);
            $data = json_decode($json, true);
            
            if (isset($data['latitude'])) {
                return [
                    'lat' => $data['latitude'],
                    'lng' => $data['longitude'],
                    'city' => $data['city'],
                    'country' => $data['country_code'],
                    'source' => 'geolocation-db'
                ];
            }
        }
        return null;
    }

    public function getDefaultLocation()
    {
        return [
            'lat' => 5.3543,
            'lng' => -4.0016,
            'city' => 'Abidjan',
            'country' => 'CI',
            'source' => 'default'
        ];
    }
}