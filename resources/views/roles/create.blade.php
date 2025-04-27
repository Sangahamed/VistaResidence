@extends('layouts.app')

@section('title', 'Créer un rôle')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Créer un nouveau rôle</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Définissez les informations et les permissions du rôle.</p>
        </div>
        <div class="border-t border-gray-200">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="is_default" class="block text-sm font-medium text-gray-700">Rôle par défaut</label>
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <input id="is_default" name="is_default" type="checkbox" value="1" {{ old('is_default') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_default" class="ml-2 block text-sm text-gray-900">
                                        Attribuer automatiquement ce rôle aux nouveaux utilisateurs
                                    </label>
                                </div>
                            </div>
                            @error('is_default')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <h4 class="text-md font-medium text-gray-700 mb-2">Permissions</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="permission_{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="permission_{{ $permission->id }}" class="font-medium text-gray-700">{{ $permission->name }}</label>
                                            <p class="text-gray-500">{{ $permission->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('roles.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Créer le rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
