<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyFeatureController extends Controller
{
    /**
     * Display a listing of the property features.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propertyFeatures = PropertyFeature::all();
        return view('property-features.index', compact('propertyFeatures'));
    }

    /**
     * Show the form for creating a new property feature.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = [
            'intérieur' => 'Intérieur',
            'extérieur' => 'Extérieur',
            'sécurité' => 'Sécurité',
            'confort' => 'Confort',
            'technologie' => 'Technologie',
            'durabilité' => 'Durabilité',
            'autre' => 'Autre'
        ];

        return view('property-features.create', compact('categories'));
    }

    /**
     * Store a newly created property feature in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:property_features',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PropertyFeature::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('property-features.index')
            ->with('success', 'Caractéristique de propriété créée avec succès.');
    }

    /**
     * Display the specified property feature.
     *
     * @param  \App\Models\PropertyFeature  $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function show(PropertyFeature $propertyFeature)
    {
        return view('property-features.show', compact('propertyFeature'));
    }

    /**
     * Show the form for editing the specified property feature.
     *
     * @param  \App\Models\PropertyFeature  $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function edit(PropertyFeature $propertyFeature)
    {
        $categories = [
            'intérieur' => 'Intérieur',
            'extérieur' => 'Extérieur',
            'sécurité' => 'Sécurité',
            'confort' => 'Confort',
            'technologie' => 'Technologie',
            'durabilité' => 'Durabilité',
            'autre' => 'Autre'
        ];

        return view('property-features.edit', compact('propertyFeature', 'categories'));
    }

    /**
     * Update the specified property feature in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PropertyFeature  $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropertyFeature $propertyFeature)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:property_features,name,' . $propertyFeature->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $propertyFeature->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('property-features.index')
            ->with('success', 'Caractéristique de propriété mise à jour avec succès.');
    }

    /**
     * Remove the specified property feature from storage.
     *
     * @param  \App\Models\PropertyFeature  $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function destroy(PropertyFeature $propertyFeature)
    {
        // Check if this feature is used by any properties
        if ($propertyFeature->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cette caractéristique ne peut pas être supprimée car elle est utilisée par des propriétés.');
        }

        $propertyFeature->delete();

        return redirect()->route('property-features.index')
            ->with('success', 'Caractéristique de propriété supprimée avec succès.');
    }
}

