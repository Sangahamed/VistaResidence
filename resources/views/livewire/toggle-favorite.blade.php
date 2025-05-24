<div>
<button wire:click="toggle"
        class="favorite-btn absolute top-4 right-4 z-10 p-2 bg-white/80 rounded-full shadow-md transition-all duration-300 hover:bg-white hover:scale-110">
    <svg class="w-6 h-6"
        fill="{{ $isFavorite ? '#ef4444' : 'none' }}"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
        </path>
    </svg>
</button>

</div>