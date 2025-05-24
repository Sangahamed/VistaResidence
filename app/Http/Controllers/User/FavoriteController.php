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
        $favorite = $property->favorites()->where('user_id', auth()->id())->first();
        
        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'action' => 'removed']);
        } else {
            $property->favorites()->create(['user_id' => auth()->id()]);
            return response()->json(['success' => true, 'action' => 'added']);
        }
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
