<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyComparisonController extends Controller
{
    /**
     * Afficher la page de comparaison de propriétés.
     */
    public function index(Request $request)
    {
        // Récupérer les IDs des propriétés à comparer depuis la session
        $propertyIds = session('comparison_list', []);
        
        // Récupérer les propriétés
        $properties = Property::whereIn('id', $propertyIds)->get();
        
        return view('properties.comparison', compact('properties'));
    }
    
    /**
     * Ajouter une propriété à la liste de comparaison.
     */
    public function add(Request $request, Property $property)
    {
        // Récupérer la liste actuelle
        $comparisonList = session('comparison_list', []);
        
        // Vérifier si la propriété est déjà dans la liste
        if (!in_array($property->id, $comparisonList)) {
            // Limiter à 4 propriétés maximum
            if (count($comparisonList) >= 4) {
                return redirect()->back()
                    ->with('error', 'Vous ne pouvez pas comparer plus de 4 propriétés à la fois.');
            }
            
            // Ajouter la propriété à la liste
            $comparisonList[] = $property->id;
            session(['comparison_list' => $comparisonList]);
            
            return redirect()->back()
                ->with('success', 'La propriété a été ajoutée à votre liste de comparaison.');
        }
        
        return redirect()->back()
            ->with('info', 'Cette propriété est déjà dans votre liste de comparaison.');
    }
    
    /**
     * Retirer une propriété de la liste de comparaison.
     */
    public function remove(Request $request, Property $property)
    {
        // Récupérer la liste actuelle
        $comparisonList = session('comparison_list', []);
        
        // Retirer la propriété de la liste
        $comparisonList = array_diff($comparisonList, [$property->id]);
        session(['comparison_list' => $comparisonList]);
        
        return redirect()->back()
            ->with('success', 'La propriété a été retirée de votre liste de comparaison.');
    }
    
    /**
     * Vider la liste de comparaison.
     */
    public function clear(Request $request)
    {
        // Vider la liste
        session(['comparison_list' => []]);
        
        return redirect()->route('properties.index')
            ->with('success', 'Votre liste de comparaison a été vidée.');
    }
}
