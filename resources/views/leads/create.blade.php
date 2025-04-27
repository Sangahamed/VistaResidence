@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add New Lead</h1>
        <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Leads
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('leads.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Contact Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" required>
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address (Optional)</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Lead Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="proposal" {{ old('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                            <option value="closed_won" {{ old('status') == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                            <option value="closed_lost" {{ old('status') == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="source" class="block text-gray-700 text-sm font-bold mb-2">Source</label>
                        <select name="source" id="source" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('source') border-red-500 @enderror" required>
                            <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="email" {{ old('source') == 'email' ? 'selected' : '' }}>Email Campaign</option>
                            <option value="phone" {{ old('source') == 'phone' ? 'selected' : '' }}>Phone Inquiry</option>
                            <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('source')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="agent_id" class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                        <select name="agent_id" id="agent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('agent_id') border-red-500 @enderror">
                            <option value="">Unassigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Property Preferences</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="property_type" class="block text-gray-700 text-sm font-bold mb-2">Property Type</label>
                        <select name="property_type" id="property_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('property_type') border-red-500 @enderror">
                            <option value="">Any</option>
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
                    
                    <div>
                        <label for="budget_min" class="block text-gray-700 text-sm font-bold mb-2">Min Budget</label>
                        <input type="number" name="budget_min" id="budget_min" value="{{ old('budget_min') }}" min="0" step="1000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('budget_min') border-red-500 @enderror">
                        @error('budget_min')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="budget_max" class="block text-gray-700 text-sm font-bold mb-2">Max Budget</label>
                        <input type="number" name="budget_max" id="budget_max" value="{{ old('budget_max') }}" min="0" step="1000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('budget_max') border-red-500 @enderror">
                        @error('budget_max')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="bedrooms" class="block text-gray-700 text-sm font-bold mb-2">Bedrooms</label>
                        <select name="bedrooms" id="bedrooms" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bedrooms') border-red-500 @enderror">
                            <option value="">Any</option>
                            <option value="1" {{ old('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ old('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ old('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ old('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                            <option value="5" {{ old('bedrooms') == '5' ? 'selected' : '' }}>5+</option>
                        </select>
                        @error('bedrooms')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bathrooms" class="block text-gray-700 text-sm font-bold mb-2">Bathrooms</label>
                        <select name="bathrooms" id="bathrooms" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('bathrooms') border-red-500 @enderror">
                            <option value="">Any</option>
                            <option value="1" {{ old('bathrooms') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ old('bathrooms') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ old('bathrooms') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ old('bathrooms') == '4' ? 'selected' : '' }}>4+</option>
                        </select>
                        @error('bathrooms')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Preferred Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
