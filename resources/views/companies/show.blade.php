@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $company->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Détails de l'entreprise et gestion des ressources.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nom de l'entreprise</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $company->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Secteur d'activité</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $company->industry ?? 'Non spécifié' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Contact principal</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $company->contact_name }}<br>
                        {{ $company->contact_email }}<br>
                        {{ $company->contact_phone ?? 'Aucun téléphone' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Site web</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if ($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="text-rose-600 hover:text-rose-900">{{ $company->website }}</a>
                        @else
                            Non spécifié
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if ($company->address)
                            {{ $company->address }}<br>
                            {{ $company->city ?? '' }} {{ $company->postal_code ?? '' }}<br>
                            {{ $company->country ?? '' }}
                        @else
                            Non spécifiée
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $company->description ?? 'Aucune description' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Propriétaire</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($company->owner->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $company->owner->name }}">
                            {{ $company->owner->name }}
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Modules activés</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex flex-wrap gap-2">
                            @forelse ($company->modules as $module)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                    {{ $module->name }}
                                </span>
                            @empty
                                Aucun module activé
                            @endforelse
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Statistiques -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Statistiques</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Aperçu des ressources de l'entreprise.</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="grid grid-cols-2 divide-x divide-gray-200">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $company->teams->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Équipes</p>
                    </div>
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $company->users->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Utilisateurs</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 divide-x divide-gray-200 border-t border-gray-200">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $company->projects->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Projets</p>
                    </div>
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $tasksCount }}</span>
                        <p class="mt-1 text-sm text-gray-500">Tâches</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projets récents -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Projets récents</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Les derniers projets de l'entreprise.</p>
                </div>
                <a href="{{ route('projects.index', ['company' => $company->id]) }}" class="text-sm font-medium text-rose-600 hover:text-rose-500">
                    Voir tous
                </a>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($recentProjects as $project)
                        <li>
                            <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-amber-100">
                                                <span class="text-amber-700 font-medium text-lg">{{ substr($project->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $project->status === 'not_started' ? 'bg-gray-100 text-gray-800' : 
                                                  ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                  ($project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                {{ $project->status === 'not_started' ? 'Non démarré' : 
                                                  ($project->status === 'in_progress' ? 'En cours' : 
                                                  ($project->status === 'on_hold' ? 'En pause' : 'Terminé')) }}
                                            </span>
                                            <svg class="ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-5 text-center text-sm text-gray-500">
                            Aucun projet récent
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Équipes -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Équipes</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Les équipes de l'entreprise.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('teams.index', ['company' => $company->id]) }}" class="text-sm font-medium text-rose-600 hover:text-rose-500">
                        Voir toutes
                    </a>
                    <a href="{{ route('teams.create', ['company' => $company->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ajouter
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($company->teams as $team)
                        <li>
                            <a href="{{ route('teams.show', ['company' => $company->id, 'team' => $team->id]) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100">
                                                <span class="text-indigo-700 font-medium text-lg">{{ substr($team->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $team->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $team->users->count() }} membres</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            @if ($team->leader)
                                                <div class="flex items-center mr-2">
                                                    <img class="h-6 w-6 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($team->leader->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $team->leader->name }}">
                                                    <span class="ml-1 text-xs text-gray-500">{{ $team->leader->name }}</span>
                                                </div>
                                            @endif
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-5 text-center text-sm text-gray-500">
                            Aucune équipe
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Utilisateurs -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Utilisateurs</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Les membres de l'entreprise.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('users.index', ['company' => $company->id]) }}" class="text-sm font-medium text-rose-600 hover:text-rose-500">
                        Voir tous
                    </a>
                    <a href="{{ route('invitations.create', ['company' => $company->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Inviter
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($company->users->take(5) as $user)
                        <li>
                            <a href="{{ route('users.show', $user) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-xs text-gray-500">{{ $user->pivot->job_title ?? 'Membre' }}</span>
                                            <svg class="ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-5 text-center text-sm text-gray-500">
                            Aucun utilisateur
                        </li>
                    @endforelse
                </ul>
                @if ($company->users->count() > 5)
                    <div class="px-4 py-3 bg-gray-50 text-center text-sm">
                        <a href="{{ route('users.index', ['company' => $company->id]) }}" class="font-medium text-rose-600 hover:text-rose-500">
                            Voir tous les utilisateurs ({{ $company->users->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
