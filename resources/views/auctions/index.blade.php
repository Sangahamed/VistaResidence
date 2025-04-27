@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Enchères immobilières</h1>
            <p class="text-muted-foreground">
                Découvrez les propriétés disponibles aux enchères et placez vos offres.
            </p>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <a href="{{ route('auctions.index', ['status' => 'active']) }}" 
                class="px-4 py-2 rounded-md {{ $status === 'active' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                Enchères en cours
            </a>
            <a href="{{ route('auctions.index', ['status' => 'upcoming']) }}" 
                class="px-4 py-2 rounded-md {{ $status === 'upcoming' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                Enchères à venir
            </a>
            <a href="{{ route('auctions.index', ['status' => 'ended']) }}" 
                class="px-4 py-2 rounded-md {{ $status === 'ended' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                Enchères terminées
            </a>
            @auth
                <a href="{{ route('auctions.history') }}" 
                    class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 ml-auto">
                    Mes enchères
                </a>
            @endauth
        </div>

        @if($auctions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($auctions as $auction)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="relative">
                            @if($auction->property->featured_image)
                                <img src="{{ asset('storage/' . $auction->property->featured_image) }}" 
                                    alt="{{ $auction->property->title }}" 
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <div class="absolute top-0 right-0 m-2">
                                @if($auction->status === 'active')
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        En cours
                                    </span>
                                @elseif($auction->status === 'upcoming')
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        À venir
                                    </span>
                                @elseif($auction->status === 'ended')
                                    <span class="bg-gray-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        Terminée
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        Annulée
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">
                                <a href="{{ route('auctions.show', $auction) }}" class="hover:text-primary">
                                    {{ $auction->property->title }}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                {{ $auction->property->address }}, {{ $auction->property->city }}
                            </p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Enchère actuelle</p>
                                    <p class="text-xl font-bold text-primary">
                                        {{ $auction->current_bid ? number_format($auction->current_bid, 0, ',', ' ') . ' €' : 'Aucune enchère' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Prix de départ</p>
                                    <p class="text-gray-700">
                                        {{ number_format($auction->starting_price, 0, ',', ' ') }} €
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Enchères</p>
                                    <p class="text-gray-700">{{ $auction->total_bids }}</p>
                                </div>
                                <div class="text-right">
                                    @if($auction->status === 'active')
                                        <p class="text-sm text-gray-500">Fin dans</p>
                                        <p class="text-gray-700 countdown" data-end="{{ $auction->end_date->timestamp }}">
                                            {{ $auction->end_date->diffForHumans() }}
                                        </p>
                                    @elseif($auction->status === 'upcoming')
                                        <p class="text-sm text-gray-500">Début dans</p>
                                        <p class="text-gray-700">
                                            {{ $auction->start_date->diffForHumans() }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500">Terminée le</p>
                                        <p class="text-gray-700">
                                            {{ $auction->end_date->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <a href="{{ route('auctions.show', $auction) }}" class="block w-full bg-primary text-white text-center py-2 rounded-md hover:bg-primary-dark">
                                @if($auction->status === 'active')
                                    Enchérir maintenant
                                @elseif($auction->status === 'upcoming')
                                    Voir les détails
                                @else
                                    Voir les résultats
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $auctions->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-gavel text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aucune enchère disponible</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($status === 'active')
                        Il n'y a actuellement aucune enchère en cours.
                    @elseif($status === 'upcoming')
                        Il n'y a actuellement aucune enchère à venir.
                    @else
                        Il n'y a actuellement aucune enchère terminée.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update countdown timers
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const endTime = parseInt(el.dataset.end) * 1000;
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance <= 0) {
                el.textContent = 'Terminée';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if (days > 0) {
                el.textContent = `${days}j ${hours}h ${minutes}m`;
            } else {
                el.textContent = `${hours}h ${minutes}m ${seconds}s`;
            }
        });
    }
    
    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns();
</script>
@endsection