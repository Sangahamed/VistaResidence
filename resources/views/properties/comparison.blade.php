@extends('components.back.layout.back')

@section('content')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        /* Animation pour les lignes du tableau */
        tr {
            opacity: 0;
            animation: fade-in 0.3s ease-out forwards;
        }

        /* Délai progressif pour chaque ligne */
        tr:nth-child(1) {
            animation-delay: 0.1s;
        }

        tr:nth-child(2) {
            animation-delay: 0.2s;
        }

        tr:nth-child(3) {
            animation-delay: 0.3s;
        }

        tr:nth-child(4) {
            animation-delay: 0.4s;
        }

        tr:nth-child(5) {
            animation-delay: 0.5s;
        }

        tr:nth-child(6) {
            animation-delay: 0.6s;
        }
    </style>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Comparaison de propriétés
                </h1>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Comparez jusqu'à 4 propriétés côte à côte
                </p>
            </div>

            @if ($properties->isEmpty())
                <div class="text-center py-12 bg-white rounded-lg shadow-md animate-fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Liste de comparaison vide</h3>
                    <p class="mt-1 text-gray-500">Ajoutez des propriétés à comparer depuis les fiches détaillées.</p>
                    <div class="mt-6">
                        <a href="{{ route('properties.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            Parcourir les propriétés
                        </a>
                    </div>
                </div>
            @else
                <div class="flex justify-between items-center mb-6">
                    <div class="text-sm text-gray-500">
                        {{ $properties->count() }} propriété(s) à comparer
                    </div>
                    <form action="{{ route('properties.comparison.clear') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm text-red-600 hover:text-red-800 flex items-center transition-all duration-200 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Vider la liste
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Caractéristique</th>
                                        @foreach ($properties as $property)
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 relative">
                                                <div class="group">
                                                    <div class="flex items-center">
                                                        @if (!empty($property->images) && count($property->images) > 0)
                                                            <img src="{{ Storage::url($property->images[0]['path']) }}"
                                                                alt="{{ $property->title }}"
                                                                class="h-10 w-10 rounded-md object-cover mr-3">
                                                        @else
                                                            <div
                                                                class="w-12 h-12 rounded-md bg-gray-100 flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="font-medium">{{ $property->title }}</p>
                                                            <p class="text-xs text-gray-500">{{ $property->location }}</p>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('properties.comparison.remove', $property) }}"
                                                        method="POST"
                                                        class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        @csrf
                                                        <button type="submit" class="text-gray-400 hover:text-red-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Prix</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ number_format($property->price, 0, ',', ' ') }} €</td>
                                        @endforeach
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Surface</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $property->surface }} m²</td>
                                        @endforeach
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Pièces</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $property->rooms }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Chambres</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $property->bedrooms }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Type</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $property->type }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            Statut</td>
                                        @foreach ($properties as $property)
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $property->status === 'à vendre' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $property->status }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
