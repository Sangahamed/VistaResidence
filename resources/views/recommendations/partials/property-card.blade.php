@props(['property', 'fullWidth' => false])

<div class="{{ $fullWidth ? 'w-full' : 'w-full' }} bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
    <div class="relative h-48 overflow-hidden">
        @if($property->images && is_array(json_decode($property->images, true)))
            <img src="{{ Storage::url(json_decode($property->images, true)[0]) }}" 
                 alt="{{ $property->title }}" 
                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
        @else
            <div class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
        <div class="absolute top-0 left-0 bg-indigo-600 text-white px-2 py-1 m-2 rounded text-xs font-bold">
            {{ $property->type === 'apartment' ? 'Appartement' : 
              ($property->type === 'house' ? 'Maison' : 
              ($property->type === 'villa' ? 'Villa' : 
              ($property->type === 'land' ? 'Terrain' : 'Commercial'))) }}
        </div>
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
            <span class="text-white font-bold">@money($property->price)</span>
        </div>
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $property->title }}</h3>
        <p class="text-gray-600 text-sm mb-3 truncate">{{ $property->address }}</p>
        <div class="flex justify-between text-xs text-gray-500">
            <span>{{ $property->bedrooms ?? 'N/A' }} chambres</span>
            <span>{{ $property->bathrooms ?? 'N/A' }} sdb</span>
            <span>{{ $property->area ?? 'N/A' }} m²</span>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <span class="text-xs text-gray-500">Ajouté {{ $property->created_at->diffForHumans() }}</span>
            <a href="{{ route('properties.show', $property->id) }}" 
               class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm flex items-center">
                Voir plus
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>