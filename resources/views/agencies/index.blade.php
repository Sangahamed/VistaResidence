@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Agences immobilières</h1>
        <a href="{{ route('agencies.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ajouter une agence
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($agencies as $agency)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-40 bg-gray-200 flex items-center justify-center">
                @if($agency->logo)
                <img src="{{ Storage::url($agency->logo) }}" alt="{{ $agency->name }}" class="h-full w-full object-cover">
                @else
                <div class="text-gray-500 text-xl font-semibold">{{ $agency->name }}</div>
                @endif
            </div>
            <div class="p-4">
                <h2 class="text-xl font-semibold mb-2">{{ $agency->name }}</h2>
                <p class="text-gray-600 mb-2">{{ $agency->city }}, {{ $agency->state }}</p>
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                    </svg>
                    <span class="text-gray-600">{{ $agency->agents_count }} agents</span>
                </div>
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="text-gray-600">{{ $agency->active_listings_count ?? 0 }} propriétés</span>
                </div>
                <div class="flex justify-between">
                    <a href="{{ route('agencies.show', $agency) }}" class="text-blue-600 hover:text-blue-800">Voir détails</a>
                    <div>
                        <a href="{{ route('agencies.edit', $agency) }}" class="text-indigo-600 hover:text-indigo-800 mr-2">Modifier</a>
                        <form action="{{ route('agencies.destroy', $agency) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette agence?')">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $agencies->links() }}
    </div>
</div>
@endsection