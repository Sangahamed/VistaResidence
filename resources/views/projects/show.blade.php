@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $project->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Projet de {{ $company->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.kanban', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Kanban
                </a>
                <a href="{{ route('projects.gantt', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Gantt
                </a>
                <a href="{{ route('projects.edit', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('projects.destroy', ['company' => $company->id, 'project' => $project->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                    <dt class="text-sm font-medium text-gray-500">Nom du projet</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $project->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $project->status === 'not_started' ? 'bg-gray-100 text-gray-800' : 
                              ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                              ($project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                            {{ $project->status === 'not_started' ? 'Non démarré' : 
                              ($project->status === 'in_progress' ? 'En cours' : 
                              ($project->status === 'on_hold' ? 'En pause' : 'Terminé')) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $project->description ?? 'Aucune description' }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Responsable</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if ($project->manager)
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($project->manager->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $project->manager->name }}">
                                {{ $project->manager->name }}
                            </div>
                        @else
                            Non assigné
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Dates</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <span class="mr-2">Début:</span>
                            <span class="font-medium">{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}</span>
                        </div>
                        <div class="flex items-center mt-1">
                            <span class="mr-2">Fin:</span>
                            <span class="font-medium {{ $project->end_date && $project->end_date->isPast() && $project->status !== 'completed' ? 'text-red-600' : '' }}">
                                {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}
                                @if ($project->end_date && $project->end_date->isPast() && $project->status !== 'completed')
                                    (En retard)
                                @endif
                            </span>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Progression</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="bg-amber-600 h-2.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span>{{ $project->progress }}%</span>
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if ($project->budget)
                            {{ number_format($project->budget, 2, ',', ' ') }} €
                        @else
                            Non défini
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Priorité</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $project->priority === 'low' ? 'bg-green-100 text-green-800' : 
                              ($project->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($project->priority) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Statistiques -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Statistiques</h3>
            </div>
            <div class="border-t border-gray-200">
                <div class="grid grid-cols-2 divide-x divide-gray-200">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $tasks->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Tâches</p>
                    </div>
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $completedTasks }}</span>
                        <p class="mt-1 text-sm text-gray-500">Terminées</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 divide-x divide-gray-200 border-t border-gray-200">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $members->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Membres</p>
                    </div>
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $teams->count() }}</span>
                        <p class="mt-1 text-sm text-gray-500">Équipes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membres -->
        <div class="md:col-span-2 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Membres du projet</h3>
                <a href="{{ route('projects.edit', ['company' => $company->id, 'project' => $project->id]) }}#members" class="text-sm font-medium text-amber-600 hover:text-amber-500">
                    Gérer les membres
                </a>
            </div>
            <div class="border-t border-gray-200">
                <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4">
                    @forelse ($members as $member)
                        <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
                            <div class="w-full flex items-center justify-between p-4">
                                <div class="flex-1 flex items-center truncate">
                                    <img class="w-10 h-10 flex-shrink-0 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $member->name }}">
                                    <div class="flex-1 px-4 truncate">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ $member->email }}</div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $member->pivot->role ?? 'Membre' }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="col-span-3 text-center py-4 text-sm text-gray-500">
                            Aucun membre assigné à ce projet
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Tâches -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tâches</h3>
            <div class="flex space-x-3">
                <a href="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" class="text-sm font-medium text-amber-600 hover:text-amber-500">
                    Voir toutes
                </a>
                <a href="{{ route('tasks.create', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
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
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="hover:text-amber-600">
                                                    {{ $task->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
                                    @if ($task->assignee)
                                        <div class="flex items-center">
                                            <img class="h-6 w-6 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                            <div class="text-sm text-gray-900">{{ $task->assignee->name }}</div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Non assigné</span>
                                    @endif
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
                                    <a href="{{ route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="text-amber-600 hover:text-amber-900 mr-3">Voir</a>
                                    <a href="{{ route('tasks.edit', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune tâche pour ce projet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($tasks->count() > 5)
                <div class="px-4 py-3 bg-gray-50 text-center text-sm">
                    <a href="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" class="font-medium text-amber-600 hover:text-amber-500">
                        Voir toutes les tâches ({{ $tasks->count() }})
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
