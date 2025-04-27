@extends('layouts.app')

@section('title', $team->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $team->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Équipe de {{ $company->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('teams.edit', ['company' => $company->id, 'team' => $team->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('teams.destroy', ['company' => $company->id, 'team' => $team->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                    <dt class="text-sm font-medium text-gray-500">Nom de l'équipe</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $team->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $team->description ?? 'Aucune description' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Responsable</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if ($team->leader)
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($team->leader->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $team->leader->name }}">
                                {{ $team->leader->name }}
                            </div>
                        @else
                            Non assigné
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $company->name }}
                        </a>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nombre de membres</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $members->count() }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Projets associés</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $projects->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Membres -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Membres de l'équipe</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('teams.edit', ['company' => $company->id, 'team' => $team->id]) }}#members" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Gérer les membres
                    </a>
                    <button type="button" onclick="document.getElementById('invite-form').classList.toggle('hidden')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Inviter
                    </button>
                </div>
            </div>
            <div id="invite-form" class="hidden px-4 py-3 bg-gray-50 border-t border-gray-200">
                <form action="{{ route('teams.invite', ['company' => $company->id, 'team' => $team->id]) }}" method="POST">
                    @csrf
                    <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
                        <div class="flex-grow">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Adresse email" required>
                        </div>
                        <div>
                            <label for="role_id" class="sr-only">Rôle</label>
                            <select id="role_id" name="role_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Rôle par défaut</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Envoyer l'invitation
                        </button>
                    </div>
                </form>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($members as $member)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $member->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $member->pivot->role ?? 'Membre' }}
                                    </span>
                                    @if ($team->leader_id === $member->id)
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Responsable
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-5 text-center text-sm text-gray-500">
                            Aucun membre dans cette équipe
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Projets -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Projets associés</h3>
                <a href="{{ route('projects.create', ['company' => $company->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau projet
                </a>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($projects as $project)
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
                            Aucun projet associé à cette équipe
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Tâches -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tâches assignées à l'équipe</h3>
            <a href="{{ route('tasks.index', ['company' => $company->id, 'team' => $team->id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Voir toutes
            </a>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tasks->take(5) as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full 
                                            {{ $task->status === 'to_do' ? 'bg-gray-100' : 
                                              ($task->status === 'in_progress' ? 'bg-blue-100' : 
                                              ($task->status === 'review' ? 'bg-purple-100' : 'bg-green-100')) }}">
                                            <svg class="h-5 w-5 
                                                {{ $task->status === 'to_do' ? 'text-gray-500' : 
                                                  ($task->status === 'in_progress' ? 'text-blue-500' : 
                                                  ($task->status === 'review' ? 'text-purple-500' : 'text-green-500')) }}" 
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if($task->status === 'completed')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @endif
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $task->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $task->status === 'to_do' ? 'bg-gray-100 text-gray-800' : 
                                          ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                          ($task->status === 'review' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800')) }}">
                                        {{ $task->status === 'to_do' ? 'À faire' : 
                                          ($task->status === 'in_progress' ? 'En cours' : 
                                          ($task->status === 'review' ? 'En révision' : 'Terminé')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $task->priority === 'low' ? 'bg-green-100 text-green-800' : 
                                          ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($task->due_date)
                                        <span class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                            @if ($task->isOverdue())
                                                <span class="text-xs ml-1">(En retard)</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Non définie</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('tasks.show', ['company' => $company->id, 'project' => $task->project_id, 'task' => $task->id]) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune tâche assignée à cette équipe
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($tasks->count() > 5)
                <div class="px-4 py-3 bg-gray-50 text-center text-sm">
                    <a href="{{ route('tasks.index', ['company' => $company->id, 'team' => $team->id]) }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Voir toutes les tâches ({{ $tasks->count() }})
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
