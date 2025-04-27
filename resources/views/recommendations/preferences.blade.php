@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Mes préférences</h1>
            <p class="text-muted-foreground">
                Personnalisez vos préférences pour recevoir des recommandations adaptées à vos besoins.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <form action="{{ route('recommendations.preferences.update') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Localisation</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="preferred_locations" class="block text-sm font-medium text-gray-700 mb-1">
                                        Villes ou codes postaux préférés
                                    </label>
                                    <select id="preferred_locations" name="preferred_locations[]" multiple
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                        @foreach(['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier'] as $city)
                                            <option value="{{ $city }}" {{ $preferences->preferred_locations && in_array($city, $preferences->preferred_locations) ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Vous pouvez sélectionner plusieurs villes (Ctrl+clic ou Cmd+clic).
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Type de bien</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="preferred_property_types" class="block text-sm font-medium text-gray-700 mb-1">
                                        Types de biens préférés
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(['apartment' => 'Appartement', 'house' => 'Maison', 'villa' => 'Villa', 'land' => 'Terrain', 'commercial' => 'Commercial'] as $value => $label)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="property_type_{{ $value }}" name="preferred_property_types[]" value="{{ $value }}"
                                                    {{ $preferences->preferred_property_types && in_array($value, $preferences->preferred_property_types) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                                <label for="property_type_{{ $value }}" class="ml-2 text-sm text-gray-700">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Budget</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">
                                        Prix minimum
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" name="min_price" id="min_price" 
                                            value="{{ $preferences->min_price }}"
                                            class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">
                                        Prix maximum
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" name="max_price" id="max_price" 
                                            value="{{ $preferences->max_price }}"
                                            class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Caractéristiques</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="min_bedrooms" class="block text-sm font-medium text-gray-700 mb-1">
                                        Chambres (min)
                                    </label>
                                    <select id="min_bedrooms" name="min_bedrooms"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                        <option value="">Indifférent</option>
                                        @foreach(range(1, 5) as $num)
                                            <option value="{{ $num }}" {{ $preferences->min_bedrooms == $num ? 'selected' : '' }}>
                                                {{ $num }}+
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="min_bathrooms" class="block text-sm font-medium text-gray-700 mb-1">
                                        Salles de bain (min)
                                    </label>
                                    <select id="min_bathrooms" name="min_bathrooms"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                        <option value="">Indifférent</option>
                                        @foreach(range(1, 3) as $num)
                                            <option value="{{ $num }}" {{ $preferences->min_bathrooms == $num ? 'selected' : '' }}>
                                                {{ $num }}+
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="min_surface" class="block text-sm font-medium text-gray-700 mb-1">
                                        Surface minimale (m²)
                                    </label>
                                    <input type="number" name="min_surface" id="min_surface" 
                                        value="{{ $preferences->min_surface }}"
                                        class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Équipements</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_garden" name="has_garden" type="checkbox" value="1" 
                                                {{ $preferences->has_garden ? 'checked' : '' }}
                                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_garden" class="font-medium text-gray-700">Jardin</label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_balcony" name="has_balcony" type="checkbox" value="1" 
                                                {{ $preferences->has_balcony ? 'checked' : '' }}
                                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_balcony" class="font-medium text-gray-700">Balcon/Terrasse</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_parking" name="has_parking" type="checkbox" value="1" 
                                                {{ $preferences->has_parking ? 'checked' : '' }}
                                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_parking" class="font-medium text-gray-700">Parking/Garage</label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_elevator" name="has_elevator" type="checkbox" value="1" 
                                                {{ $preferences->has_elevator ? 'checked' : '' }}
                                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_elevator" class="font-medium text-gray-700">Ascenseur</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Autres préférences</h2>
                            <div>
                                <label for="preferred_amenities" class="block text-sm font-medium text-gray-700 mb-1">
                                    Équipements supplémentaires
                                </label>
                                <select id="preferred_amenities" name="preferred_amenities[]" multiple
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                    @foreach(['pool' => 'Piscine', 'gym' => 'Salle de sport', 'security' => 'Sécurité', 'air_conditioning' => 'Climatisation', 'fireplace' => 'Cheminée'] as $value => $label)
                                        <option value="{{ $value }}" {{ $preferences->preferred_amenities && in_array($value, $preferences->preferred_amenities) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    Vous pouvez sélectionner plusieurs équipements (Ctrl+clic ou Cmd+clic).
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200 mt-8">
                        <div class="flex justify-end">
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Enregistrer mes préférences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection