@extends('layouts.app') @section('title', 'Créer une entreprise')
@section('header', 'Créer une entreprise') @section('content')
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                La création d'une entreprise vous permettra de gérer des
                équipes, des projets et des propriétés en tant qu'entité
                professionnelle. Votre demande sera examinée par un
                administrateur.
            </p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data"
    class="bg-white p-6 rounded-lg shadow-md">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nom de l'entreprise *
            </label>
            <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}"
                required />
            @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="logo">
                Logo
            </label>
            <input class="form-input" id="logo" type="file" name="logo" accept="image/*" />
            @error('logo')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description *
            </label>
            <textarea class="form-input" id="description" name="description" rows="4" required>
{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email professionnel *
            </label>
            <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}"
                required />
            @error('email')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                Téléphone professionnel *
            </label>
            <input class="form-input" id="phone" type="text" name="phone" value="{{ old('phone') }}"
                required />
            @error('phone')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="website">
                Site web
            </label>
            <input class="form-input" id="website" type="url" name="website" value="{{ old('website') }}" />
            @error('website')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                Adresse *
            </label>
            <input class="form-input" id="address" type="text" name="address" value="{{ old('address') }}"
                required />
            @error('address')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="city">
                    Ville *
                </label>
                <input class="form-input" id="city" type="text" name="city" value="{{ old('city') }}" required />
                @error('city')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="postal_code">
                    Code postal *
                </label>
                <input class="form-input" id="postal_code" type="text" name="postal_code"
                    value="{{ old('postal_code') }}" required />
                @error('postal_code')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="country">
                    Pays *
                </label>
                <input class="form-input" id="country" type="text" name="country" value="{{ old('country') }}"
                    required />
                @error('country')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-primary">Créer mon entreprise</button>
        </div>
    </form>
@endsection
