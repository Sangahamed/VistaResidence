@extends('layouts.app') @section('title', 'Modifier mon entreprise')
@section('header', 'Modifier mon entreprise') @section('content')
<form
    method="POST"
    action="{{ route('company.update') }}"
    enctype="multipart/form-data"
    class="bg-white p-6 rounded-lg shadow-md"
>
    @csrf @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="name"
            >
                Nom de l'entreprise *
            </label>
            <input
                class="form-input"
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $company->name) }}"
                required
            />
            @error('name')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="logo"
            >
                Logo
            </label>
            <input
                class="form-input"
                id="logo"
                type="file"
                name="logo"
                accept="image/*"
            />
            @if($company->logo)
            <div class="mt-2">
                <img
                    src="{{ asset('storage/' . $company->logo) }}"
                    alt="Logo"
                    class="h-16"
                />
            </div>
            @endif @error('logo')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="description"
            >
                Description *
            </label>
            <textarea
                class="form-input"
                id="description"
                name="description"
                rows="4"
                required
            >
{{ old('description', $company->description) }}</textarea
            >
            @error('description')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="email"
            >
                Email professionnel *
            </label>
            <input
                class="form-input"
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $company->email) }}"
                required
            />
            @error('email')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="phone"
            >
                Téléphone professionnel *
            </label>
            <input
                class="form-input"
                id="phone"
                type="text"
                name="phone"
                value="{{ old('phone', $company->phone) }}"
                required
            />
            @error('phone')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="website"
            >
                Site web
            </label>
            <input
                class="form-input"
                id="website"
                type="url"
                name="website"
                value="{{ old('website', $company->website) }}"
            />
            @error('website')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="address"
            >
                Adresse *
            </label>
            <input
                class="form-input"
                id="address"
                type="text"
                name="address"
                value="{{ old('address', $company->address) }}"
                required
            />
            @error('address')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="city"
            >
                Ville *
            </label>
            <input
                class="form-input"
                id="city"
                type="text"
                name="city"
                value="{{ old('city', $company->city) }}"
                required
            />
            @error('city')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="postal_code"
            >
                Code postal *
            </label>
            <input
                class="form-input"
                id="postal_code"
                type="text"
                name="postal_code"
                value="{{ old('postal_code', $company->postal_code) }}"
                required
            />
            @error('postal_code')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label
                class="block text-gray-700 text-sm font-bold mb-2"
                for="country"
            >
                Pays *
            </label>
            <input
                class="form-input"
                id="country"
                type="text"
                name="country"
                value="{{ old('country', $company->country) }}"
                required
            />
            @error('country')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="btn-primary">
            Mettre à jour mon entreprise
        </button>
    </div>
</form>
@endsection
