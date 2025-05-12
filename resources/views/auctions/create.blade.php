@extends('components.back.layout.back')

@section('content')
<main class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create New Auction</h1>
        <a href="{{ route('auctions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Auctions
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('auctions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Property Information</h2>
                
                <div class="mb-4">
                    <label for="property_id" class="block text-gray-700 text-sm font-bold mb-2">Select Property</label>
                    <select name="property_id" id="property_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('property_id') border-red-500 @enderror" required>
                        <option value="">-- Select a property --</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                {{ $property->title }} - {{ Str::limit($property->address, 30) }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Or Create New Property</label>
                    <div class="flex items-center">
                        <input type="checkbox" id="create_new_property" name="create_new_property" class="mr-2" {{ old('create_new_property') ? 'checked' : '' }}>
                        <label for="create_new_property" class="text-sm text-gray-700">Create a new property instead</label>
                    </div>
                </div>
                
                <div id="new_property_fields" class="{{ old('create_new_property') ? '' : 'hidden' }}">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Property Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Property Description</label>
                        <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="bedrooms" class="block text-gray-700 text-sm font-bold mb-2">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bedrooms') border-red-500 @enderror">
                            @error('bedrooms')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="bathrooms" class="block text-gray-700 text-sm font-bold mb-2">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" min="0" step="0.5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bathrooms') border-red-500 @enderror">
                            @error('bathrooms')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="square_feet" class="block text-gray-700 text-sm font-bold mb-2">Square Feet</label>
                            <input type="number" name="square_feet" id="square_feet" value="{{ old('square_feet') }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('square_feet') border-red-500 @enderror">
                            @error('square_feet')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="property_type" class="block text-gray-700 text-sm font-bold mb-2">Property Type</label>
                        <select name="property_type" id="property_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('property_type') border-red-500 @enderror">
                            <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>House</option>
                            <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="condo" {{ old('property_type') == 'condo' ? 'selected' : '' }}>Condo</option>
                            <option value="townhouse" {{ old('property_type') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                            <option value="land" {{ old('property_type') == 'land' ? 'selected' : '' }}>Land</option>
                            <option value="commercial" {{ old('property_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        </select>
                        @error('property_type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Property Images</label>
                        <input type="file" name="images[]" id="images" multiple class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('images') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">You can select multiple images. First image will be used as featured image.</p>
                        @error('images')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Auction Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="auction_date" class="block text-gray-700 text-sm font-bold mb-2">Auction Date</label>
                        <input type="datetime-local" name="auction_date" id="auction_date" value="{{ old('auction_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('auction_date') border-red-500 @enderror" required>
                        @error('auction_date')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date (Optional)</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="starting_bid" class="block text-gray-700 text-sm font-bold mb-2">Starting Bid ($)</label>
                        <input type="number" name="starting_bid" id="starting_bid" value="{{ old('starting_bid') }}" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('starting_bid') border-red-500 @enderror" required>
                        @error('starting_bid')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="reserve_price" class="block text-gray-700 text-sm font-bold mb-2">Reserve Price ($) (Optional)</label>
                        <input type="number" name="reserve_price" id="reserve_price" value="{{ old('reserve_price') }}" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('reserve_price') border-red-500 @enderror">
                        @error('reserve_price')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="bid_increment" class="block text-gray-700 text-sm font-bold mb-2">Bid Increment ($)</label>
                    <input type="number" name="bid_increment" id="bid_increment" value="{{ old('bid_increment', 100) }}" min="1" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bid_increment') border-red-500 @enderror" required>
                    @error('bid_increment')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="auction_type" class="block text-gray-700 text-sm font-bold mb-2">Auction Type</label>
                    <select name="auction_type" id="auction_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('auction_type') border-red-500 @enderror" required>
                        <option value="live" {{ old('auction_type') == 'live' ? 'selected' : '' }}>Live Auction</option>
                        <option value="online" {{ old('auction_type') == 'online' ? 'selected' : '' }}>Online Auction</option>
                        <option value="hybrid" {{ old('auction_type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Live & Online)</option>
                    </select>
                    @error('auction_type')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="location_fields" class="{{ old('auction_type') == 'online' ? 'hidden' : '' }}">
                    <div class="mb-4">
                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Auction Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="terms" class="block text-gray-700 text-sm font-bold mb-2">Auction Terms & Conditions</label>
                    <textarea name="terms" id="terms" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('terms') border-red-500 @enderror">{{ old('terms', 'Standard auction terms apply. All sales are final. Payment due within 24 hours of auction close.') }}</textarea>
                    @error('terms')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Auction
                </button>
            </div>
        </form>
    </div>
</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createNewPropertyCheckbox = document.getElementById('create_new_property');
        const propertyIdSelect = document.getElementById('property_id');
        const newPropertyFields = document.getElementById('new_property_fields');
        
        createNewPropertyCheckbox.addEventListener('change', function() {
            if (this.checked) {
                propertyIdSelect.disabled = true;
                newPropertyFields.classList.remove('hidden');
            } else {
                propertyIdSelect.disabled = false;
                newPropertyFields.classList.add('hidden');
            }
        });
        
        const auctionTypeSelect = document.getElementById('auction_type');
        const locationFields = document.getElementById('location_fields');
        
        auctionTypeSelect.addEventListener('change', function() {
            if (this.value === 'online') {
                locationFields.classList.add('hidden');
            } else {
                locationFields.classList.remove('hidden');
            }
        });
        
        // Initialize state
        if (createNewPropertyCheckbox.checked) {
            propertyIdSelect.disabled = true;
            newPropertyFields.classList.remove('hidden');
        }
        
        if (auctionTypeSelect.value === 'online') {
            locationFields.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
