@extends('components.back.layout.back')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Propriétés similaires</h1>
            <p class="text-gray-600 mt-2">Découvrez des biens comparables à celui que vous consultez</p>
        </div>
        <a href="{{ route('properties.show', $property->id) }}" 
           class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la propriété
        </a>
    </div>

    <!-- Propriété de référence -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Propriété de référence</h2>
            @include('partials.property-card', ['property' => $property, 'fullWidth' => true])
        </div>
    </div>

    <!-- Propriétés similaires -->
    @if($similarProperties->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($similarProperties as $similar)
                @include('partials.property-card', ['property' => $similar])
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune propriété similaire trouvée</h3>
            <p class="mt-1 text-gray-500">Nous n'avons pas trouvé de biens similaires pour le moment.</p>
        </div>
    @endif
</div>
@endsection