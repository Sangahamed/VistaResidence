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
    $user = auth()->user();
    $favorite = $property->favorites()->where('user_id', $user->id)->first();
    
    if ($favorite) {
        $favorite->delete();
        $isFavorite = false;
    } else {
        $property->favorites()->create(['user_id' => $user->id]);
        $isFavorite = true;
    }
    
    return response()->json([
        'success' => true,
        'action' => $isFavorite ? 'added' : 'removed',
        'is_favorite' => $isFavorite
    ]);
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
