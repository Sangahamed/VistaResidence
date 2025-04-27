<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the property types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propertyTypes = PropertyType::all();
        return view('property-types.index', compact('propertyTypes'));
    }

    /**
     * Show the form for creating a new property type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('property-types.create');
    }

    /**
     * Store a newly created property type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:property_types',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PropertyType::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('property-types.index')
            ->with('success', 'Type de propriété créé avec succès.');
    }

    /**
     * Display the specified property type.
     *
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Http\Response
     */
    public function show(PropertyType $propertyType)
    {
        return view('property-types.show', compact('propertyType'));
    }

    /**
     * Show the form for editing the specified property type.
     *
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Http\Response
     */
    public function edit(PropertyType $propertyType)
    {
        return view('property-types.edit', compact('propertyType'));
    }

    /**
     * Update the specified property type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropertyType $propertyType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:property_types,name,' . $propertyType->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $propertyType->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('property-types.index')
            ->with('success', 'Type de propriété mis à jour avec succès.');
    }

    /**
     * Remove the specified property type from storage.
     *
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PropertyType $propertyType)
    {
        // Check if this property type is used by any properties
        if ($propertyType->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Ce type de propriété ne peut pas être supprimé car il est utilisé par des propriétés.');
        }

        $propertyType->delete();

        return redirect()->route('property-types.index')
            ->with('success', 'Type de propriété supprimé avec succès.');
    }
}
