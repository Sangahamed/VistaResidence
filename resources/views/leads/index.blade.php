@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Leads Management</h1>
        <a href="{{ route('leads.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Lead
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <form action="{{ route('leads.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto">
                    <label for="search" class="block text-gray-700 text-sm font-bold mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, Email, Phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="w-full md:w-auto">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negotiation" {{ request('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="closed_won" {{ request('status') == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                        <option value="closed_lost" {{ request('status') == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label for="source" class="block text-gray-700 text-sm font-bold mb-2">Source</label>
                    <select name="source" id="source" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All Sources</option>
                        <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="social_media" {{ request('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="email" {{ request('source') == 'email' ? 'selected' : '' }}>Email Campaign</option>
                        <option value="phone" {{ request('source') == 'phone' ? 'selected' : '' }}>Phone Inquiry</option>
                        <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label for="assigned_to" class="block text-gray-700 text-sm font-bold mb-2">Assigned To</label>
                    <select name="assigned_to" id="assigned_to" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-auto flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Filter
                    </button>
                    <a href="{{ route('leads.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Source</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assigned To</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Contact</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                        <div class="text-xs text-gray-500">Added {{ $lead->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="text-sm text-gray-900">{{ $lead->email }}</div>
                                <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $lead->status == 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $lead->status == 'contacted' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $lead->status == 'qualified' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $lead->status == 'proposal' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $lead->status == 'negotiation' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $lead->status == 'closed_won' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $lead->status == 'closed_lost' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="text-sm text-gray-900">
                                    @if($lead->agent)
                                        {{ $lead->agent->user->name }}
                                    @else
                                        <span class="text-gray-500">Unassigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="text-sm text-gray-900">
                                    @if($lead->last_contacted_at)
                                        {{ $lead->last_contacted_at->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-500">Never</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    <a href="{{ route('leads.edit', $lead->id) }}" class="text-green-500 hover:text-green-700">Edit</a>
                                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-4 text-center text-gray-500">No leads found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4">
            {{ $leads->links() }}
        </div>
    </div>
</div>
@endsection
