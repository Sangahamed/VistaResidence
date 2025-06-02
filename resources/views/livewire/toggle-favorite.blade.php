<div>
    <button wire:click="toggle"
            wire:loading.attr="disabled"
            class="favorite-btn absolute top-4 right-4 z-10 p-2 bg-white/80 rounded-full shadow-md transition-all duration-300 hover:bg-white hover:scale-110 disabled:opacity-50">
        
        <!-- Loading spinner -->
        <div wire:loading wire:target="toggle" class="absolute inset-0 flex items-center justify-center">
            <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Heart icon -->
        <svg wire:loading.remove wire:target="toggle" 
             class="w-6 h-6 transition-all duration-300"
             fill="{{ $isFavorite ? '#ef4444' : 'none' }}"
             stroke="{{ $isFavorite ? '#ef4444' : 'currentColor' }}" 
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
        </svg>
    </button>

    <!-- Messages flash (optionnel) -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('error') }}
        </div>
    @endif
</div>
