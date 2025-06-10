<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeoLocator
{
    public function locate(string $ip): ?array
    {
        if ($this->isLocalIp($ip)) {
            return null;
        }

        return Cache::remember("geo:$ip", now()->addDay(), function () use ($ip) {
            $response = Http::get("http://ip-api.com/json/$ip", [
                'fields' => 'status,country,countryCode,city,lat,lon,isp,proxy,hosting'
            ]);

            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'country' => $response->json('country'),
                    'country_code' => $response->json('countryCode'),
                    'city' => $response->json('city'),
                    'latitude' => $response->json('lat'),
                    'longitude' => $response->json('lon'),
                    'isp' => $response->json('isp'),
                    'is_proxy' => $response->json('proxy'),
                    'is_hosting' => $response->json('hosting'),
                ];
            }

            return null;
        });
    }

    protected function isLocalIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1']) || 
               substr($ip, 0, 8) === '192.168.' ||
               substr($ip, 0, 3) === '10.';
    }
}
