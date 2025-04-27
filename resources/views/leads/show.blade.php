@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Lead Details: {{ $lead->name }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('leads.edit', $lead->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Lead
            </a>
            <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Leads
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Contact Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Full Name</p>
                            <p class="font-medium">{{ $lead->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email Address</p>
                            <p class="font-medium">{{ $lead->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                            <p class="font-medium">{{ $lead->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Address</p>
                            <p class="font-medium">{{ $lead->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Property Preferences</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Property Type</p>
                            <p class="font-medium">{{ $lead->property_type ? ucfirst($lead->property_type) : 'Any' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Budget Range</p>
                            <p class="font-medium">
                                @if($lead->budget_min && $lead->budget_max)
                                    ${{ number_format($lead->budget_min) }} - ${{ number_format($lead->budget_max) }}
                                @elseif($lead->budget_min)
                                    From ${{ number_format($lead->budget_min) }}
                                @elseif($lead->budget_max)
                                    Up to ${{ number_format($lead->budget_max) }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Preferred Location</p>
                            <p class="font-medium">{{ $lead->location ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bedrooms</p>
                            <p class="font-medium">{{ $lead->bedrooms ? $lead->bedrooms.'+' : 'Any' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bathrooms</p>
                            <p class="font-medium">{{ $lead->bathrooms ? $lead->bathrooms.'+' : 'Any' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-700">Notes & Activities</h2>
                    <button id="add-note-btn" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                        Add Note
                    </button>
                </div>
                <div class="p-6">
                    <div id="add-note-form" class="hidden mb-6 p-4 bg-gray-50 rounded-md">
                        <form action="{{ route('lead-notes.store', $lead->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="note" class="block text-gray-700 text-sm font-bold mb-2">Note</label>
                                <textarea name="note" id="note" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" id="cancel-note-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Save Note
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    @if($lead->notes)
                        <div class="mb-6">
                            <h3 class="text-md font-semibold text-gray-700 mb-2">Initial Notes</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700 whitespace-pre-line">{{ $lead->notes }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($activities->count() > 0)
                        <div class="space-y-4">
                            @foreach($activities as $activity)
                                <div class="border-l-4 border-blue-500 pl-4 py-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-gray-700 whitespace-pre-line">{{ $activity->note }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                By {{ $activity->user->name }} on {{ $activity->created_at->format('M d, Y \a\t h:i A') }}
                                            </p>
                                        </div>
                                        @if(auth()->id() == $activity->user_id)
                                            <form action="{{ route('lead-notes.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No activity records found.</p>
                    @endif
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-700">Recommended Properties</h2>
                    <a href="{{ route('leads.recommendations', $lead->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                        View All
                    </a>
                </div>
                <div class="p-6">
                    @if($recommendedProperties->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($recommendedProperties as $property)
                                <div class="border rounded-md overflow-hidden flex">
                                    <div class="w-1/3">
                                        @if($property->featured_image)
                                            <img src="{{ asset($property->featured_image) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="w-2/3 p-3">
                                        <h3 class="font-medium text-gray-800 mb-1">{{ Str::limit($property->title, 40) }}</h3>
                                        <p class="text-sm text-gray-600 mb-1">${{ number_format($property->price) }}</p>
                                        <div class="flex text-xs text-gray-500 mb-2">
                                            <span class="mr-2">{{ $property->bedrooms }} beds</span>
                                            <span class="mr-2">{{ $property->bathrooms }} baths</span>
                                            <span>{{ number_format($property->square_feet) }} sq ft</span>
                                        </div>
                                        <a href="{{ route('properties.show', $property->id) }}" class="text-blue-500 hover:text-blue-700 text-xs font-medium">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recommended properties found.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Lead Status</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('leads.update-status', $lead->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Current Status</label>
                            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="qualified" {{ $lead->status == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="proposal" {{ $lead->status == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="negotiation" {{ $lead->status == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                <option value="closed_won" {{ $lead->status == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                <option value="closed_lost" {{ $lead->status == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status_note" class="block text-gray-700 text-sm font-bold mb-2">Status Note (Optional)</label>
                            <textarea name="status_note" id="status_note" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Assignment</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('leads.assign', $lead->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="agent_id" class="block text-gray-700 text-sm font-bold mb-2">Assigned To</label>
                            <select name="agent_id" id="agent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Unassigned</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $lead->agent_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Lead Details</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Source</p>
                            <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Created</p>
                            <p class="font-medium">{{ $lead->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                            <p class="font-medium">{{ $lead->updated_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Last Contacted</p>
                            <p class="font-medium">
                                @if($lead->last_contacted_at)
                                    {{ $lead->last_contacted_at->format('M d, Y \a\t h:i A') }}
                                @else
                                    Never
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('messenger', ['recipient_id' => $lead->user_id]) }}" class="block w-full bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                            Send Message
                        </a>
                        <a href="{{ route('leads.schedule', $lead->id) }}" class="block w-full bg-green-500 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded">
                            Schedule Appointment
                        </a>
                        <a href="{{ route('leads.email', $lead->id) }}" class="block w-full bg-purple-500 hover:bg-purple-700 text-white text-center font-bold py-2 px-4 rounded">
                            Send Email
                        </a>
                        <form action="{{ route('leads.mark-contacted', $lead->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full bg-yellow-500 hover:bg-yellow-700 text-white text-center font-bold py-2 px-4 rounded">
                                Mark as Contacted
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addNoteBtn = document.getElementById('add-note-btn');
        const cancelNoteBtn = document.getElementById('cancel-note-btn');
        const addNoteForm = document.getElementById('add-note-form');
        
        addNoteBtn.addEventListener('click', function() {
            addNoteForm.classList.remove('hidden');
            document.getElementById('note').focus();
        });
        
        cancelNoteBtn.addEventListener('click', function() {
            addNoteForm.classList.add('hidden');
        });
    });
</script>
@endpush
@endsection
