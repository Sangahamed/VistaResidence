<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VirtualTourController extends Controller
{
    /**
     * Afficher la visite virtuelle d'une propriété.
     */
    public function show(Property $property)
    {
        if (!$property->has_virtual_tour) {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Cette propriété ne dispose pas de visite virtuelle.');
        }

        return view('properties.virtual-tour', compact('property'));
    }

    /**
     * Afficher le formulaire pour créer/modifier une visite virtuelle.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        
        return view('properties.virtual-tour-edit', compact('property'));
    }

    /**
     * Mettre à jour la visite virtuelle d'une propriété.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $request->validate([
            'virtual_tour_type' => 'required|in:basic,panoramic,3d',
            'virtual_tour_url' => 'nullable|url',
            'panoramic_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'delete_panoramic_images' => 'nullable|array',
        ]);

        // Mettre à jour le type de visite virtuelle
        $property->virtual_tour_type = $request->virtual_tour_type;
        $property->has_virtual_tour = true;
        
        // Gérer l'URL de visite 3D externe
        if ($request->virtual_tour_type === '3d' && $request->filled('virtual_tour_url')) {
            $property->virtual_tour_url = $request->virtual_tour_url;
        }
        
        // Gérer les images panoramiques
        $panoramicImages = $property->panoramic_images ?? [];
        
        // Supprimer les images sélectionnées
        if ($request->has('delete_panoramic_images')) {
            foreach ($request->delete_panoramic_images as $index) {
                if (isset($panoramicImages[$index])) {
                    Storage::delete('public/' . $panoramicImages[$index]);
                    unset($panoramicImages[$index]);
                }
            }
            // Réindexer le tableau
            $panoramicImages = array_values($panoramicImages);
        }
        
        // Ajouter de nouvelles images panoramiques
        if ($request->hasFile('panoramic_images')) {
            foreach ($request->file('panoramic_images') as $image) {
                $path = $image->store('property_panoramic', 'public');
                $panoramicImages[] = $path;
            }
        }
        
        $property->panoramic_images = $panoramicImages;
        $property->save();
        
        return redirect()->route('properties.show', $property)
            ->with('success', 'Visite virtuelle mise à jour avec succès.');
    }

    /**
     * Créer une visite virtuelle basique à partir des images existantes.
     */
    public function createBasicTour(Property $property)
    {
        $this->authorize('update', $property);
        
        // Vérifier si la propriété a des images
        if (empty($property->images)) {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Vous devez d\'abord ajouter des images à cette propriété.');
        }
        
        // Configurer la visite virtuelle basique
        $property->has_virtual_tour = true;
        $property->virtual_tour_type = 'basic';
        $property->save();
        
        return redirect()->route('properties.virtual-tour', $property)
            ->with('success', 'Visite virtuelle basique créée avec succès.');
    }

    /**
     * Supprimer la visite virtuelle d'une propriété.
     */
    public function destroy(Property $property)
    {
        $this->authorize('update', $property);
        
        // Supprimer les images panoramiques
        if (!empty($property->panoramic_images)) {
            foreach ($property->panoramic_images as $image) {
                Storage::delete('public/' . $image);
            }
        }
        
        // Réinitialiser les champs de visite virtuelle
        $property->has_virtual_tour = false;
        $property->virtual_tour_type = null;
        $property->virtual_tour_url = null;
        $property->panoramic_images = null;
        $property->save();
        
        return redirect()->route('properties.show', $property)
            ->with('success', 'Visite virtuelle supprimée avec succès.');
    }
}
