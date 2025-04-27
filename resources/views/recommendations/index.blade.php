@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Property Recommendations</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Your Preferences</h2>
        
        <form action="{{ route('recommendations.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="property_type" class="block text-gray-700 text-sm font-bold mb-2">Property Type</label>
                    <select name="property_type" id="property_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Any</option>
                        <option value="house" {{ $preferences->property_type == 'house' ? 'selected' : '' }}>House</option>
                        <option value="apartment" {{ $preferences->property_type == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="condo" {{ $preferences->property_type == 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="townhouse" {{ $preferences->property_type == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                        <option value="land" {{ $preferences->property_type == 'land' ? 'selected' : '' }}>Land</option>
                        <option value="commercial" {{ $preferences->property_type == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>
                
                <div>
                    <label for="min_price" class="block text-gray-700 text-sm font-bold mb-2">Min Price</label>
                    <input type="number" name="min_price" id="min_price" value="{{ $preferences->min_price }}" min="0" step="1000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="max_price" class="block text-gray-700 text-sm font-bold mb-2">Max Price</label>
                    <input type="number" name="max_price" id="max_price" value="{{ $preferences->max_price }}" min="0" step="1000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="min_bedrooms" class="block text-gray-700 text-sm font-bold mb-2">Min Bedrooms</label>
                    <input type="number" name="min_bedrooms" id="min_bedrooms" value="{{ $preferences->min_bedrooms }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="min_bathrooms" class="block text-gray-700 text-sm font-bold mb-2">Min Bathrooms</label>
                    <input type="number" name="min_bathrooms" id="min_bathrooms" value="{{ $preferences->min_bathrooms }}" min="0" step="0.5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="min_square_feet" class="block text-gray-700 text-sm font-bold mb-2">Min Square Feet</label>
                    <input type="number" name="min_square_feet" id="min_square_feet" value="{{ $preferences->min_square_feet }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Preferred Location</label>
                    <input type="text" name="location" id="location" value="{{ $preferences->location }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="radius" class="block text-gray-700 text-sm font-bold mb-2">Search Radius (miles)</label>
                    <input type="number" name="radius" id="radius" value="{{ $preferences->radius }}" min="1" max="100" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Features</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="has_garage" name="features[]" value="garage" {{ in_array('garage', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_garage" class="text-sm text-gray-700">Garage</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="has_pool" name="features[]" value="pool" {{ in_array('pool', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_pool" class="text-sm text-gray-700">Pool</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="has_garden" name="features[]" value="garden" {{ in_array('garden', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_garden" class="text-sm text-gray-700">Garden</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="has_ac" name="features[]" value="ac" {{ in_array('ac', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_ac" class="text-sm text-gray-700">Air Conditioning</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="has_fireplace" name="features[]" value="fireplace" {{ in_array('fireplace', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_fireplace" class="text-sm text-gray-700">Fireplace</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="has_basement" name="features[]" value="basement" {{ in_array('basement', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="has_basement" class="text-sm text-gray-700">Basement</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_waterfront" name="features[]" value="waterfront" {{ in_array('waterfront', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="is_waterfront" class="text-sm text-gray-700">Waterfront</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_furnished" name="features[]" value="furnished" {{ in_array('furnished', $preferences->features ?? []) ? 'checked' : '' }} class="mr-2">
                        <label for="is_furnished" class="text-sm text-gray-700">Furnished</label>
                    </div>
                  class="text-sm text-gray-700">Furnished</label>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Preferences
                </button>
            </div>
        </form>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recommended Properties</h2>
        
        @if($recommendations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendations as $property)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative h-48">
                            @if($property->featured_image)
                                <img src="{{ asset($property->featured_image) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 bg-blue-500 text-white px-2 py-1 m-2 rounded text-sm font-bold">
                                ${{ number_format($property->price) }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $property->title }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $property->address }}</p>
                            <div class="flex justify-between text-sm text-gray-500 mb-3">
                                <span>{{ $property->bedrooms }} beds</span>
                                <span>{{ $property->bathrooms }} baths</span>
                                <span>{{ number_format($property->square_feet) }} sq ft</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ $property->property_type }}</span>
                                <a href="{{ route('properties.show', $property->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold text-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $recommendations->links() }}
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-gray-600">No recommendations found based on your preferences.</p>
                <p class="text-gray-500 text-sm mt-2">Try adjusting your preferences or check back later for new listings.</p>
            </div>
        @endif
    </div>
    
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Property Alerts</h2>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <p class="text-gray-700 mb-4">Get notified when new properties matching your preferences are listed.</p>
            
            <form action="{{ route('property-alerts.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="alert_name" class="block text-gray-700 text-sm font-bold mb-2">Alert Name</label>
                        <input type="text" name="alert_name" id="alert_name" value="{{ old('alert_name', 'My Property Alert') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('alert_name') border-red-500 @enderror" required>
                        @error('alert_name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="frequency" class="block text-gray-700 text-sm font-bold mb-2">Alert Frequency</label>
                        <select name="frequency" id="frequency" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('frequency') border-red-500 @enderror" required>
                            <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="instant" {{ old('frequency') == 'instant' ? 'selected' : '' }}>Instant</option>
                        </select>
                        @error('frequency')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="use_preferences" name="use_preferences" value="1" checked class="mr-2">
                    <label for="use_preferences" class="text-sm text-gray-700">Use my current preferences for this alert</label>
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Alert
                    </button>
                </div>
            </form>
            
            @if($alerts->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Your Active Alerts</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Frequency</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alerts as $alert)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $alert->name }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ ucfirst($alert->frequency) }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $alert->created_at->format('M d, Y') }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('property-alerts.edit', $alert->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                                <form action="{{ route('property-alerts.destroy', $alert->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this alert?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection