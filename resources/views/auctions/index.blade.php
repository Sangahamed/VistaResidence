@extends('components.back.layout.back')


@section('content')
<main class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-8">
        <!-- En-tête avec filtre -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Enchères immobilières</h1>
                <p class="text-muted-foreground text-gray-600 dark:text-gray-400">
                    Découvrez les propriétés disponibles aux enchères et placez vos offres.
                </p>
            </div>

            @auth
            <div class="flex gap-2">
                <a href="{{ route('auctions.history') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-history mr-2"></i> Mes enchères
                </a>
            </div>
            @endauth
        </div>

        <!-- Filtres -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('auctions.index', ['status' => 'active']) }}" 
               class="px-4 py-2 rounded-md text-sm font-medium transition-colors
                      {{ $status === 'active' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                <i class="fas fa-gavel mr-2"></i> Enchères en cours
            </a>
            <a href="{{ route('auctions.index', ['status' => 'upcoming']) }}" 
               class="px-4 py-2 rounded-md text-sm font-medium transition-colors
                      {{ $status === 'upcoming' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                <i class="fas fa-clock mr-2"></i> Enchères à venir
            </a>
            <a href="{{ route('auctions.index', ['status' => 'ended']) }}" 
               class="px-4 py-2 rounded-md text-sm font-medium transition-colors
                      {{ $status === 'ended' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                <i class="fas fa-flag-checkered mr-2"></i> Enchères terminées
            </a>
        </div>

        <!-- Liste des enchères -->
        @if($auctions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($auctions as $auction)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg hover:-translate-y-1">
                        <div class="relative">
                            <!-- Image de la propriété -->
                            @if($auction->property->featured_image)
                                <img src="{{ asset('storage/' . $auction->property->featured_image) }}" 
                                    alt="{{ $auction->property->title }}" 
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Badge de statut -->
                            <div class="absolute top-2 right-2">
                                @if($auction->status === 'active')
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-white mr-1 animate-pulse"></span>
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
                            
                            <!-- Compte à rebours -->
                            @if($auction->status === 'active')
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white px-2 py-1 rounded-md text-xs">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span class="countdown" data-end="{{ $auction->end_date->timestamp }}">
                                        {{ $auction->end_date->diffForHumans() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Contenu de la carte -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                                <a href="{{ route('auctions.show', $auction) }}" class="hover:text-primary">
                                    {{ $auction->property->title }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                <span>{{ $auction->property->address }}, {{ $auction->property->city }}</span>
                            </div>
                            
                            <!-- Informations sur les prix -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Prix de départ</p>
                                    <p class="text-gray-700 dark:text-gray-300 font-medium">
                                        {{ number_format($auction->starting_price, 0, ',', ' ') }} €
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Enchère actuelle</p>
                                    <p class="text-xl font-bold text-primary">
                                        {{ $auction->current_bid ? number_format($auction->current_bid, 0, ',', ' ') . ' €' : 'Aucune offre' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Métriques -->
                            <div class="flex justify-between items-center text-sm mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-friends mr-1 text-gray-500"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $auction->total_bids }} enchères</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-eye mr-1 text-gray-500"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $auction->views }} vues</span>
                                </div>
                            </div>
                            
                            <!-- Bouton d'action -->
                            <a href="{{ route('auctions.show', $auction) }}" 
                               class="block w-full text-center py-2 px-4 rounded-md font-medium transition-colors
                                      @if($auction->status === 'active')
                                          bg-primary hover:bg-primary-dark text-white
                                      @else
                                          bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white
                                      @endif">
                                @if($auction->status === 'active')
                                    <i class="fas fa-gavel mr-2"></i> Enchérir maintenant
                                @elseif($auction->status === 'upcoming')
                                    <i class="fas fa-info-circle mr-2"></i> Voir les détails
                                @else
                                    <i class="fas fa-chart-bar mr-2"></i> Voir les résultats
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $auctions->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <!-- Aucune enchère disponible -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-gavel text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aucune enchère disponible</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($status === 'active')
                        Il n'y a actuellement aucune enchère en cours. Revenez plus tard!
                    @elseif($status === 'upcoming')
                        Aucune enchère programmée pour le moment.
                    @else
                        Aucune enchère terminée à afficher.
                    @endif
                </p>
                @if($status !== 'active')
                    <a href="{{ route('auctions.index', ['status' => 'active']) }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md text-sm font-medium">
                        Voir les enchères en cours
                    </a>
                @endif
            </div>
        @endif
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compte à rebours
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const endTime = parseInt(el.dataset.end) * 1000;
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance <= 0) {
                el.textContent = 'Terminée';
                el.classList.add('text-red-500');
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if (days > 0) {
                el.innerHTML = `${days}<span class="text-xs">j</span> ${hours}<span class="text-xs">h</span> ${minutes}<span class="text-xs">m</span>`;
            } else if (hours > 0) {
                el.innerHTML = `${hours}<span class="text-xs">h</span> ${minutes}<span class="text-xs">m</span> ${seconds}<span class="text-xs">s</span>`;
            } else {
                el.innerHTML = `${minutes}<span class="text-xs">m</span> ${seconds}<span class="text-xs">s</span>`;
            }
        });
    }
    
    // Mettre à jour les comptes à rebours chaque seconde
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
    
    // Animation au survol des cartes
    const auctionCards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md');
    auctionCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.querySelector('img')?.classList.add('scale-105', 'transition-transform', 'duration-300');
        });
        card.addEventListener('mouseleave', () => {
            card.querySelector('img')?.classList.remove('scale-105');
        });
    });
});
</script>
@endsection