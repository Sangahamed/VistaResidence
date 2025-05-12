@extends('layouts.app')

@section('title', 'Modifier mon entreprise')
@section('header', 'Modifier mon entreprise')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Formulaire -->
    <form method="POST" action="{{ route('company.update') }}" enctype="multipart/form-data" 
          class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg animate-slide-up">
        @csrf 
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom de l'entreprise -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                    Nom de l'entreprise *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="name" type="text" name="name" value="{{ old('name', $company->name) }}" required />
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="logo">
                    Logo
                </label>
                <div class="mt-1 flex items-center">
                    <label for="logo" class="cursor-pointer">
                        <span class="inline-block px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Changer le logo
                        </span>
                        <input id="logo" name="logo" type="file" class="sr-only" accept="image/*" />
                    </label>
                    @if($company->logo)
                    <div class="ml-4 flex-shrink-0">
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo actuel" class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                    </div>
                    @endif
                </div>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="description">
                    Description *
                </label>
                <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                          id="description" name="description" rows="4" required>{{ old('description', $company->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email professionnel -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                    Email professionnel *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="email" type="email" name="email" value="{{ old('email', $company->email) }}" required />
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone professionnel -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="phone">
                    Téléphone professionnel *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="phone" type="text" name="phone" value="{{ old('phone', $company->phone) }}" required />
                @error('phone')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Site web -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="website">
                    Site web
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="website" type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="https://example.com" />
                @error('website')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Adresse -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="address">
                    Adresse *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="address" type="text" name="address" value="{{ old('address', $company->address) }}" required />
                @error('address')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ville -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="city">
                    Ville *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="city" type="text" name="city" value="{{ old('city', $company->city) }}" required />
                @error('city')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code postal -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="postal_code">
                    Code postal *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                       id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" required />
                @error('postal_code')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pays -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="country">
                    Pays *
                </label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-150 ease-in-out" 
                        id="country" name="country" required>
                    <option value="">Sélectionnez un pays</option>
                    <option value="France" {{ old('country', $company->country) == 'France' ? 'selected' : '' }}>France</option>
                    <option value="Belgique" {{ old('country', $company->country) == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                    <option value="Suisse" {{ old('country', $company->country) == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                    <option value="Luxembourg" {{ old('country', $company->country) == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                    <option value="Canada" {{ old('country', $company->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="Autre" {{ old('country', $company->country) == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('country')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de soumission -->
            <div class="md:col-span-2 mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Mettre à jour mon entreprise
                </button>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
@endsection