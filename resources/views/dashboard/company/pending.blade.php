@extends('layouts.app') @section('title', 'Entreprise en attente')
@section('header', 'Entreprise en attente d\'approbation') @section('content')
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg
                class="h-5 w-5 text-yellow-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path
                    fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"
                />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700">
                Votre demande de création d'entreprise est en cours d'examen.
                Vous serez notifié par email lorsqu'elle sera approuvée.
            </p>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold mb-4">Détails de votre entreprise</h3>
    <div class="space-y-2">
        <div class="flex">
            <span class="text-gray-600 w-24">Nom:</span>
            <span>{{ Auth::user()->company->name }}</span>
        </div>
        <div class="flex">
            <span class="text-gray-600 w-24">Email:</span>
            <span>{{ Auth::user()->company->email }}</span>
        </div>
        <div class="flex">
            <span class="text-gray-600 w-24">Téléphone:</span>
            <span>{{ Auth::user()->company->phone }}</span>
        </div>
        <div class="flex">
            <span class="text-gray-600 w-24">Adresse:</span>
            <span
                >{{ Auth::user()->company->address }}, {{
                Auth::user()->company->city }}, {{
                Auth::user()->company->postal_code }}, {{
                Auth::user()->company->country }}</span
            >
        </div>
        <div class="flex">
            <span class="text-gray-600 w-24">Description:</span>
            <span>{{ Auth::user()->company->description }}</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('company.edit') }}" class="btn-primary inline-block">
            Modifier les informations
        </a>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-lg font-semibold mb-4">En attendant l'approbation</h3>
    <p class="text-gray-600">
        Vous pouvez continuer à utiliser le site en tant que particulier. Une
        fois votre entreprise approuvée, vous aurez accès à toutes les
        fonctionnalités d'entreprise.
    </p>
</div>
@endsection