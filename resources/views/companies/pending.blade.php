@extends('components.back.layout.back')

@section('title', 'Entreprise en attente')
@section('header', 'Entreprise en attente d\'approbation')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Notification d'avertissement -->
    <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mb-8 rounded-r-lg shadow-sm animate-fade-in">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 dark:text-yellow-200">
                    Votre demande de création d'entreprise est en cours d'examen. Vous serez notifié par email lorsqu'elle sera approuvée.
                </p>
            </div>
        </div>
    </div>

    <!-- Carte des détails de l'entreprise -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg animate-slide-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Détails de votre entreprise</h3>
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
            @endif
        </div>
        
        <div class="space-y-3">
            <div class="flex flex-col sm:flex-row">
                <span class="text-gray-600 dark:text-gray-400 w-32 font-medium">Nom:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $company->name }}</span>
            </div>
            <div class="flex flex-col sm:flex-row">
                <span class="text-gray-600 dark:text-gray-400 w-32 font-medium">Email:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $company->email }}</span>
            </div>
            <div class="flex flex-col sm:flex-row">
                <span class="text-gray-600 dark:text-gray-400 w-32 font-medium">Téléphone:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $company->phone }}</span>
            </div>
            <div class="flex flex-col sm:flex-row">
                <span class="text-gray-600 dark:text-gray-400 w-32 font-medium">Adresse:</span>
                <span class="text-gray-800 dark:text-gray-200">
                    {{ $company->address }}, {{ $company->city }}, {{ $company->postal_code }}, {{ $company->country }}
                </span>
            </div>
            <div class="flex flex-col sm:flex-row">
                <span class="text-gray-600 dark:text-gray-400 w-32 font-medium">Description:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $company->description }}</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('companies.edit', $company->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier les informations
            </a>
        </div>
    </div>

    <!-- Section d'information -->
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">En attendant l'approbation</h3>
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-gray-600 dark:text-gray-300">
                    Vous pouvez continuer à utiliser le site en tant que particulier. Une fois votre entreprise approuvée, vous aurez accès à toutes les fonctionnalités d'entreprise.
                </p>
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition duration-150 ease-in-out">
                        Retour au tableau de bord
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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