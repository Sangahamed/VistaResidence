@extends('layouts.app')

@section('title', 'Tâches')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tâches du projet {{ $project->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez les tâches de votre projet.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au projet
                </a>
                <a href="{{ route('tasks.create', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle tâche
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <form action="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" method="GET" class="flex flex-wrap items-center space-y-2 md:space-y-0 md:space-x-4">
                    <div class="flex-1 min-w-0 md:max-w-xs">
                        <label for="search" class="sr-only">Rechercher</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-sky-500 focus:border-sky-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Rechercher une tâche">
                        </div>
                    </div>
                    <div>
                        <label for="status" class="sr-only">Statut</label>
                        <select id="status" name="status" class="focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Tous les statuts</option>
                            <option value="to_do" {{ request('status') == 'to_do' ? 'selected' : '' }}>À faire</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>En révision</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="sr-only">Priorité</label>
                        <select id="priority" name="priority" class="focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Toutes les priorités</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                        </select>
                    </div>
                    <div>
                        <label for="assignee_id" class="sr-only">Assigné à</label>
                        <select id="assignee_id" name="assignee_id" class="focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Tous les assignés</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('assignee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            Filtrer
                        </button>
                        <a href="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
            @if ($tasks->isEmpty())
                <div class="px-4 py-5 sm:p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une tâche pour votre projet.</p>
                    <div class="mt-6">
                        <a href="{{ route('tasks.create', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouvelle tâche
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progression</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4">
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
                                                    <a href="{{ route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="hover:text-sky-600">
                                                        {{ $task->name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
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
                                                <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($task->subtasks->count() > 0)
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-sky-600 h-2.5 rounded-full" style="width: {{ $task->progress }}%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-900">{{ $task->progress }}%</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="text-sky-600 hover:text-sky-900 mr-3">Voir</a>
                                        <a href="{{ route('tasks.edit', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                                        <form action="{{ route('tasks.destroy', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
