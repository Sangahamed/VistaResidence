@extends('components.front.layouts.auth')

@section('title', 'Vérification du compte')

@section('content')


<!-- Container principal -->
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-orange-600/50 bg-slate-400">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-lg w-full text-center fade-in">
        <!-- Section icône et titre -->
        <div class="mb-6">
            <div class="text-green-500 text-6xl mb-4 bounce">
                &#10003;
                <!-- Symbole de coche -->
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Vérifiez votre adresse e-mail !</h1>
        </div>

        <!-- Section message -->
        <div class="mb-6">
            <p class="text-gray-700 leading-relaxed">
                Un lien de vérification a été envoyé à :
                <strong>{{ session('unverified_email') }}</strong>
            </p>
            <p>Si vous n'avez pas reçu l'e-mail, vous pouvez demander un nouveau lien.</p>
            </p>
        </div>
        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mt-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                {{ session('info') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session('unverified_email'))

            <form class="mt-8 space-y-2" action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <button type="submit" class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-black/45 transition duration-300 transform hover:scale-105">
                    Renvoyer le lien de vérification
                </button>
            </form>
        @else
            <p class="text-red-500">
                Aucun e-mail d'inscription n'a été trouvé. Veuillez vous inscrire à nouveau.
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-black/45 transition duration-300 transform hover:scale-105">Retour à
                l'inscription</a>
        @endif

        <!-- Bouton d'action -->
        <div>
            <a href=""
                class=" mt-5 inline-block bg-orange-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-black/45 transition duration-300 transform hover:scale-105">
                Accédez à votre compte
            </a>
        </div>
    </div>
</div>
@endsection
