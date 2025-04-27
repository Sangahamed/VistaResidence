@extends('layouts.app')

@section('title', 'Modules')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Modules et Extensions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez les modules disponibles pour votre application.</p>
            </div>
            <div>
                <a href="{{ route('modules.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau module
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            @if ($modules->isEmpty())
                <div class="px-4 py-5 sm:p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun module</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer un module pour étendre les fonctionnalités de votre application.</p>
                    <div class="mt-6">
                        <a href="{{ route('modules.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouveau module
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                    @foreach ($modules as $module)
                        <div class="bg-white overflow-hidden shadow rounded-lg border {{ $module->is_enabled ? 'border-green-200' : 'border-gray-200' }}">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $module->name }}</h3>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $module->is_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $module->is_enabled ? 'Activé' : 'Désactivé' }}
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Version {{ $module->version }}
                                </div>
                                <p class="mt-3 text-sm text-gray-500">{{ $module->description ?? 'Aucune description' }}</p>
                                <div class="mt-4">
                                    @if ($module->is_core)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                                            Module principal
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ $module->companies->count() }} entreprises
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between">
                                <a href="{{ route('modules.show', $module) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Voir les détails
                                </a>
                                <div>
                                    <a href="{{ route('modules.edit', $module) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-500 mr-3">
                                        Modifier
                                    </a>
                                    @if (!$module->is_core)
                                        <form action="{{ route('modules.destroy', $module) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce module ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
