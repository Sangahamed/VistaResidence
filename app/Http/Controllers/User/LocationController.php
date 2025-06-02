<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'accuracy' => 'nullable|numeric'
        ]);

        if (auth()->check()) {
            $user = auth()->user();
            $user->location()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                    'accuracy' => $data['accuracy'] ?? 10000,
                    'source' => 'ip_geolocation'
                ]
            );
        } else {
            session()->put('guest_location', [
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'accuracy' => $data['accuracy'] ?? 10000
            ]);
        }

        return response()->json(['success' => true]);
    }
}
