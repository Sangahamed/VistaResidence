@extends('layouts.app')

@section('title', 'Modifier le rôle du membre')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Modifier le rôle de {{ $member->name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Équipe: {{ $team->name }}</p>
        </div>
        <div class="border-t border-gray-200">
            <form action="{{ route('teams.members.update', [$team, $member]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rôle dans l'équipe</label>
                            <input type="text" name="role" id="role" value="{{ old('role', $role) }}" class="mt-1 focus:ring-rose-500 focus:border-rose-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('teams.members.index', $team) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        Mettre à jour le rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
