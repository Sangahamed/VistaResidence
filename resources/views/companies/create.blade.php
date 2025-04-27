@extends('layouts.app')

@section('title', 'Créer une entreprise')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Créer une nouvelle entreprise</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Remplissez les informations pour créer une nouvelle entreprise.</p>
        </div>
        <div class="border-t border-gray-200">
            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="industry" class="block text-sm font-medium text-gray-700">Secteur d'activité</label>
                            <input type="text" name="industry" id="industry" value="{{ old('industry') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('industry')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="contact_name" class="block text-sm font-medium text-gray-700">Nom du contact</label>
                            <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Email du contact</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Téléphone du contact</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="website" class="block text-sm font-medium text-gray-700">Site web</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Code postal</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <div class="mt-1 flex items-center">
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </span>
                                <input type="file" name="logo" id="logo" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Modules</label>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($modules as $module)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="module_{{ $module->id }}" name="modules[]" type="checkbox" value="{{ $module->id }}" {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }} class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="module_{{ $module->id }}" class="font-medium text-gray-700">{{ $module->name }}</label>
                                            <p class="text-gray-500">{{ $module->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('modules')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('companies.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        Créer l'entreprise
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
