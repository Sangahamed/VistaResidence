@extends('layouts.app')

@section('title', 'Membres de l\'équipe ' . $team->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Membres de l'équipe: {{ $team->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des membres de l'équipe et leurs rôles.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('companies.teams.show', [$team->company, $team]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'équipe
                </a>
                @can('update', $team)
                <a href="{{ route('teams.members.create', $team) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter un membre
                </a>
                @endcan
            </div>
        </div>
        <div class="border-t border-gray-200">
            @if ($members->isEmpty())
                <div class="px-4 py-5 sm:p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun membre</h3>
                    <p class="mt-1 text-sm text-gray-500">Cette équipe n'a pas encore de membres.</p>
                    @can('update', $team)
                    <div class="mt-6">
                        <a href="{{ route('teams.members.create', $team) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Ajouter un membre
                        </a>
                    </div>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle dans l'équipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'ajout</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $member->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $member->pivot->role }}</div>
                                        @if ($team->leader_id === $member->id)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Leader
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->pivot->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @can('update', $team)
                                            @if ($team->leader_id !== $member->id)
                                                <a href="{{ route('teams.members.edit', [$team, $member]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier le rôle</a>
                                                <form action="{{ route('teams.members.destroy', [$team, $member]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce membre de l\'équipe ?')">Retirer</button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
        
        @can('update', $team)
        <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
            <h4 class="text-lg leading-6 font-medium text-gray-900">Inviter un nouveau membre</h4>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Envoyez une invitation par email à un nouveau membre.</p>
            
            <form action="{{ route('teams.invite', $team) }}" method="POST" class="mt-4">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" class="shadow-sm focus:ring-rose-500 focus:border-rose-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle dans l'équipe</label>
                        <div class="mt-1">
                            <input type="text" name="role" id="role" class="shadow-sm focus:ring-rose-500 focus:border-rose-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Envoyer l'invitation
                    </button>
                </div>
            </form>
        </div>
        @endcan
    </div>
@endsection
