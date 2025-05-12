@extends('components.back.layout.back')

@section('content')
<main class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <a href="{{ route('auctions.index') }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Retour aux enchères
            </a>
            <h1 class="text-3xl font-bold tracking-tight">{{ $auction->property->title }}</h1>
            <p class="text-muted-foreground">
                {{ $auction->property->address }}, {{ $auction->property->city }} {{ $auction->property->postal_code }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="relative">
                        @if($auction->property->featured_image)
                            <img src="{{ asset('storage/' . $auction->property->featured_image) }}" 
                                alt="{{ $auction->property->title }}" 
                                class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
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
                    
                    <div class="p-6">
                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">Enchère actuelle</p>
                                <p class="text-3xl font-bold text-primary">
                                    {{ $auction->current_bid ? number_format($auction->current_bid, 0, ',', ' ') . ' €' : 'Aucune enchère' }}
                                </p>
                                @if($auction->current_bidder)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Meilleur enchérisseur: {{ $auction->current_bidder->name }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">Prix de départ</p>
                                <p class="text-xl text-gray-700">
                                    {{ number_format($auction->starting_price, 0, ',', ' ') }} €
                                </p>
                                @if($auction->reserve_price)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Prix de réserve: {{ $auction->isReserveMet() ? 'Atteint' : 'Non atteint' }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">Enchères</p>
                                <p class="text-xl text-gray-700">{{ $auction->total_bids }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Incrément: {{ number_format($auction->bid_increment, 0, ',', ' ') }} €
                                </p>
                            </div>
                            
                            <div class="flex-1">
                                @if($auction->status === 'active')
                                    <p class="text-sm text-gray-500">Fin dans</p>
                                    <p class="text-xl text-gray-700 countdown" data-end="{{ $auction->end_date->timestamp }}">
                                        {{ $auction->end_date->diffForHumans() }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $auction->end_date->format('d/m/Y H:i') }}
                                    </p>
                                @elseif($auction->status === 'upcoming')
                                    <p class="text-sm text-gray-500">Début dans</p>
                                    <p class="text-xl text-gray-700 countdown" data-end="{{ $auction->start_date->timestamp }}">
                                        {{ $auction->start_date->diffForHumans() }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $auction->start_date->format('d/m/Y H:i') }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500">Terminée le</p>
                                    <p class="text-xl text-gray-700">
                                        {{ $auction->end_date->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="border-t pt-6">
                            <h2 class="text-xl font-semibold mb-4">Description de la propriété</h2>
                            <p class="text-gray-700">
                                {{ $auction->property->description }}
                            </p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                <div>
                                    <p class="text-sm text-gray-500">Type</p>
                                    <p class="text-gray-700">{{ ucfirst($auction->property->property_type) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Surface</p>
                                    <p class="text-gray-700">{{ $auction->property->surface }} m²</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Chambres</p>
                                    <p class="text-gray-700">{{ $auction->property->bedrooms }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Salles de bain</p>
                                    <p class="text-gray-700">{{ $auction->property->bathrooms }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <a href="{{ route('properties.show', $auction->property) }}" class="text-primary hover:underline">
                                    Voir tous les détails de la propriété
                                </a>
                            </div>
                        </div>
                        
                        @if($auction->terms_conditions)
                            <div class="border-t pt-6 mt-6">
                                <h2 class="text-xl font-semibold mb-4">Conditions de l'enchère</h2>
                                <div class="bg-gray-50 p-4 rounded-md text-sm">
                                    {{ $auction->terms_conditions }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Historique des enchères</h2>
                        
                        @if($recentBids->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Enchérisseur
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Montant
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Type
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentBids as $bid)
                                            <tr class="{{ $bid->is_winning ? 'bg-green-50' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $bid->user->name }}
                                                            @if($bid->is_winning)
                                                                <span class="ml-2 text-xs text-green-600">
                                                                    (Meilleure enchère)
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ number_format($bid->amount, 0, ',', ' ') }} €
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $bid->created_at->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bid->is_auto_bid ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $bid->is_auto_bid ? 'Automatique' : 'Manuelle' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Aucune enchère n'a encore été placée.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden sticky top-6">
                    <div class="p-6">
                        @if($auction->status === 'active')
                            <h2 class="text-xl font-semibold mb-4">Placer une enchère</h2>
                            
                            @auth
                                @if(auth()->id() !== $auction->property->user_id)
                                    <form action="{{ route('auctions.bid', $auction) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-4">
                                            <label for="bid_amount" class="block text-sm font-medium text-gray-700">Montant de l'enchère</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" name="bid_amount" id="bid_amount" 
                                                    class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                                    placeholder="0" 
                                                    min="{{ $auction->getMinimumBidAmount() }}" 
                                                    step="{{ $auction->bid_increment }}"
                                                    value="{{ $auction->getMinimumBidAmount() }}">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">€</span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Enchère minimum: {{ number_format($auction->getMinimumBidAmount(), 0, ',', ' ') }} €
                                            </p>
                                        </div>
                                        
                                        <div class="mb-6">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="is_auto_bid" name="is_auto_bid" type="checkbox" value="1" 
                                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="is_auto_bid" class="font-medium text-gray-700">Enchère automatique</label>
                                                    <p class="text-gray-500">
                                                        Le système enchérira automatiquement pour vous jusqu'à votre montant maximum.
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div id="auto_bid_section" class="mt-4 hidden">
                                                <label for="max_auto_bid_amount" class="block text-sm font-medium text-gray-700">Montant maximum</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <input type="number" name="max_auto_bid_amount" id="max_auto_bid_amount" 
                                                        class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                                        placeholder="0" 
                                                        min="{{ $auction->getMinimumBidAmount() }}">
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">€</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                            Placer mon enchère
                                        </button>
                                    </form>
                                    
                                    @if($userAutoBid)
                                        <div class="mt-6 p-4 bg-blue-50 rounded-md">
                                            <h3 class="text-sm font-medium text-blue-800">Votre enchère automatique</h3>
                                            <p class="text-sm text-blue-700 mt-1">
                                                Montant maximum: {{ number_format($userAutoBid->max_auto_bid_amount, 0, ',', ' ') }} €
                                            </p>
                                            
                                            <div class="mt-3 flex gap-2">
                                                <button type="button" id="edit_auto_bid_btn" class="text-xs text-blue-600 hover:text-blue-800">
                                                    Modifier
                                                </button>
                                                <form action="{{ route('auctions.cancel-auto-bid', $auction) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                                        Annuler
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <div id="edit_auto_bid_form" class="mt-3 hidden">
                                                <form action="{{ route('auctions.update-auto-bid', $auction) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="edit_max_auto_bid_amount" class="block text-xs font-medium text-gray-700">Nouveau montant maximum</label>
                                                        <div class="mt-1 relative rounded-md shadow-sm">
                                                            <input type="number" name="max_auto_bid_amount" id="edit_max_auto_bid_amount" 
                                                                class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                value="{{ $userAutoBid->max_auto_bid_amount }}" 
                                                                min="{{ max($auction->getMinimumBidAmount(), $userAutoBid->max_auto_bid_amount) }}">
                                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">€</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="w-full bg-blue-600 text-white py-1 px-3 rounded-md text-sm hover:bg-blue-700">
                                                        Mettre à jour
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-yellow-50 p-4 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Vous êtes le propriétaire</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>Vous ne pouvez pas enchérir sur votre propre propriété.</p>
                                                </div>
                                                
                                                @if($auction->status === 'upcoming' && $auction->total_bids === 0)
                                                    <div class="mt-4">
                                                        <form action="{{ route('auctions.cancel', $auction) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette enchère ?');">
                                                            @csrf
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                                                Annuler l'enchère
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <p class="text-gray-700 mb-4">Vous devez être connecté pour placer une enchère.</p>
                                    <a href="{{ route('login') }}" class="block w-full bg-primary text-white text-center py-2 rounded-md hover:bg-primary-dark">
                                        Se connecter
                                    </a>
                                    <a href="{{ route('register') }}" class="block w-full text-primary text-center py-2 mt-2 hover:underline">
                                        Créer un compte
                                    </a>
                                </div>
                            @endauth
                        @elseif($auction->status === 'upcoming')
                            <h2 class="text-xl font-semibold mb-4">Enchère à venir</h2>
                            <div class="bg-blue-50 p-4 rounded-md">
                                <p class="text-blue-700">
                                    Cette enchère débutera le {{ $auction->start_date->format('d/m/Y à H:i') }}.
                                </p>
                                <p class="text-blue-700 mt-2">
                                    Revenez à cette date pour placer votre enchère.
                                </p>
                            </div>
                            
                            @auth
                                @if(auth()->id() === $auction->property->user_id && $auction->total_bids === 0)
                                    <div class="mt-6">
                                        <form action="{{ route('auctions.cancel', $auction) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette enchère ?');">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                                                Annuler l'enchère
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        @else
                            <h2 class="text-xl font-semibold mb-4">Enchère terminée</h2>
                            <div class="bg-gray-50 p-4 rounded-md">
                                @if($auction->current_bidder)
                                    <p class="text-gray-700">
                                        L'enchère a été remportée par <strong>{{ $auction->current_bidder->name }}</strong> pour un montant de <strong>{{ number_format($auction->current_bid, 0, ',', ' ') }} €</strong>.
                                    </p>
                                    
                                    @if($auction->reserve_price && !$auction->isReserveMet())
                                        <p class="text-red-600 mt-2">
                                            Le prix de réserve n'a pas été atteint.
                                        </p>
                                    @endif
                                    
                                    @auth
                                        @if(auth()->id() === $auction->current_bidder_id)
                                            <div class="mt-4 p-3 bg-green-50 rounded-md">
                                                <p class="text-green-700 font-medium">
                                                    Félicitations ! Vous avez remporté cette enchère.
                                                </p>
                                                <p class="text-green-700 mt-2">
                                                    Le vendeur vous contactera prochainement pour finaliser la transaction.
                                                </p>
                                            </div>
                                        @endif
                                    @endauth
                                @else
                                    <p class="text-gray-700">
                                        Aucune enchère n'a été placée pour cette propriété.
                                    </p>
                                @endif
                            </div>
                        @endif
                        
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Vendeur</h3>
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $auction->property->user->name }}</p>
                                    <p class="text-xs text-gray-500">Membre depuis {{ $auction->property->user->created_at->format('m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($userBids->count() > 0)
                    <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold mb-4">Vos enchères</h2>
                            <div class="space-y-3">
                                @foreach($userBids as $bid)
                                    <div class="border-b pb-3 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ number_format($bid->amount, 0, ',', ' ') }} €</span>
                                            <span class="text-sm text-gray-500">{{ $bid->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex justify-between mt-1">
                                            <span class="text-sm {{ $bid->is_winning ? 'text-green-600' : 'text-gray-500' }}">
                                                {{ $bid->is_winning ? 'Meilleure enchère' : 'Surenchéri' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $bid->is_auto_bid ? 'Automatique' : 'Manuelle' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
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
                // Reload page if auction just ended
                if (el.dataset.reload === 'true') {
                    location.reload();
                }
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
    
    // Toggle auto bid section
    const autoBidCheckbox = document.getElementById('is_auto_bid');
    const autoBidSection = document.getElementById('auto_bid_section');
    
    if (autoBidCheckbox && autoBidSection) {
        autoBidCheckbox.addEventListener('change', function() {
            if (this.checked) {
                autoBidSection.classList.remove('hidden');
            } else {
                autoBidSection.classList.add('hidden');
            }
        });
    }
    
    // Toggle edit auto bid form
    const editAutoBidBtn = document.getElementById('edit_auto_bid_btn');
    const editAutoBidForm = document.getElementById('edit_auto_bid_form');
    
    if (editAutoBidBtn && editAutoBidForm) {
        editAutoBidBtn.addEventListener('click', function() {
            editAutoBidForm.classList.toggle('hidden');
        });
    }
    
    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns();
</script>
@endsection