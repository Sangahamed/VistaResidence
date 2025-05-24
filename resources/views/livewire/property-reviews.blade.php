<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
        Avis ({{ $totalReviews }})
        <span class="text-sm font-normal">
            - Note moyenne : {{ number_format($averageRating, 1) }}/5
        </span>
    </h2>

    @auth
        @unless ($hasReviewed)
            {{-- Formulaire d'avis --}}
            <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="font-medium mb-3">Laisser un avis</h3>

                <div class="star-rating mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star cursor-pointer
                            {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-xl"
                            wire:click="setRating({{ $i }})"></i>
                    @endfor
                </div>

                <textarea wire:model.defer="comment" class="w-full p-3 border rounded-lg mb-2" placeholder="Partagez votre expérience..."></textarea>
                @error('comment')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <button wire:click.prevent="submitReview"
                    class="mt-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Publier
                </button>
            </div>
        @else
            <p class="text-green-500 mb-4">Vous avez déjà laissé un avis pour cette propriété.</p>
        @endunless
    @else
        <p class="text-gray-600 mb-4">
            Veuillez <a href="{{ route('login') }}" class="text-orange-500">vous connecter</a> pour laisser un avis.
        </p>
    @endauth

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <div class="flex items-center mb-2">
                    <img src="{{ $review->user->avatar_url ?? asset('images/default-avatar.png') }}"
                         alt="{{ $review->user->name }}"
                         class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <h4 class="font-medium">{{ $review->user->name }}</h4>
                        <div class="flex">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300">{{ $review->comment }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500">Aucun avis pour l'instant. Soyez le premier à en laisser un !</p>
        @endforelse
    </div>

    @if (session()->has('message'))
        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>
