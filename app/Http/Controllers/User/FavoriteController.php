<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ajoute ou supprime une propriété des favoris.
     */
    public function toggle(Property $property)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->first();
        
        if ($favorite) {
            // Si la propriété est déjà en favori, la supprimer
            $favorite->delete();
            $message = 'Propriété retirée des favoris.';
        } else {
            // Sinon, l'ajouter aux favoris
            Favorite::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            $message = 'Propriété ajoutée aux favoris.';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Affiche la liste des propriétés favorites de l'utilisateur.
     */
    public function index()
    {
        $favorites = Favorite::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);
        
        return view('properties.favorites', compact('favorites'));
    }
}
