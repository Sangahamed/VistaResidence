@extends('components.front.layouts.auth')

@section('title', 'Vérification du compte')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-400 via-pink-500 to-red-500">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-lg w-full text-center fade-in">
        <div class="mb-6">
            <div class="text-green-500 text-6xl mb-4 bounce">&#10003;</div>
            <h1 class="text-3xl font-bold text-gray-900">Vérification à deux facteurs</h1>
        </div>

        <div class="mb-6">
            <p class="text-gray-700 leading-relaxed">
                Un code de vérification a été envoyé à votre adresse e-mail.
                Veuillez entrer ce code pour continuer.
            </p>
        </div>

        @if (session('fail'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('fail') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('admin.two-factor.verify') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="two_factor_code" class="sr-only">Code de vérification</label>
                    <input id="two_factor_code" name="two_factor_code" type="text" required
                        class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Code de vérification">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 transform hover:scale-105">
                    Vérifier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection