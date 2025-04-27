@extends('layouts.app')

@section('title', 'Détails de la tâche')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $task->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Tâche du projet <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="text-indigo-600 hover:text-indigo-900">{{ $project->name }}</a>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux tâches
                </a>
                <a href="{{ route('tasks.edit', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Détails de la tâche</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->description)
                                            {!! nl2br(e($task->description)) !!}
                                        @else
                                            <span class="text-gray-500 italic">Aucune description</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $task->status === 'to_do' ? 'bg-gray-100 text-gray-800' : 
                                              ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                              ($task->status === 'review' ? 'bg-purple-100 text-purple-800' : 
                                              'bg-green-100 text-green-800')) }}">
                                            {{ $task->status === 'to_do' ? 'À faire' : 
                                              ($task->status === 'in_progress' ? 'En cours' : 
                                              ($task->status === 'review' ? 'En révision' : 'Terminé')) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Priorité</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-800' : 
                                              ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                                              ($task->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                              'bg-purple-100 text-purple-800')) }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Assigné à</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->assignee)
                                            <div class="flex items-center">
                                                <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                                {{ $task->assignee->name }}
                                            </div>
                                        @else
                                            <span class="text-gray-500 italic">Non assigné</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Équipe</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->team)
                                            {{ $task->team->name }}
                                        @else
                                            <span class="text-gray-500 italic">Aucune équipe</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->start_date)
                                            {{ $task->start_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-500 italic">Non définie</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date d'échéance</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->due_date)
                                            <span class="{{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                                {{ $task->due_date->format('d/m/Y') }}
                                                @if ($task->isOverdue())
                                                    (En retard)
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-500 italic">Non définie</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Temps estimé</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->estimated_hours)
                                            {{ $task->estimated_hours }} heures
                                        @else
                                            <span class="text-gray-500 italic">Non défini</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Temps réel</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($task->actual_hours)
                                            {{ $task->actual_hours }} heures
                                        @else
                                            <span class="text-gray-500 italic">Non défini</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $task->creator->name }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $task->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                @if ($task->completed_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Terminé le</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $task->completed_at->format('d/m/Y H:i') }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Sous-tâches -->
                    <div class="mt-4 bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Sous-tâches</h3>
                            <button type="button" onclick="document.getElementById('add-subtask-form').classList.toggle('hidden')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Ajouter
                            </button>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <form id="add-subtask-form" action="{{ route('tasks.subtasks.add', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST" class="mb-4 hidden">
                                @csrf
                                <div class="flex">
                                    <input type="text" name="name" placeholder="Nom de la sous-tâche" class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Ajouter
                                    </button>
                                </div>
                            </form>

                            @if ($subtasks->isEmpty())
                                <p class="text-sm text-gray-500 italic">Aucune sous-tâche</p>
                            @else
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($subtasks as $subtask)
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <form action="{{ route('tasks.subtasks.toggle', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id, 'subtask' => $subtask->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="mr-3 h-5 w-5 text-gray-400 hover:text-gray-500 focus:outline-none">
                                                        @if ($subtask->is_completed)
                                                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                                <span class="text-sm {{ $subtask->is_completed ? 'line-through text-gray-500' : 'text-gray-900' }}">
                                                    {{ $subtask->name }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ $subtask->updated_at->diffForHumans() }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Commentaires -->
                    <div class="mt-4 bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Commentaires</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <form action="{{ route('tasks.comments.add', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST" class="mb-6">
                                @csrf
                                <div>
                                    <label for="content" class="sr-only">Commentaire</label>
                                    <textarea id="content" name="content" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ajouter un commentaire..."></textarea>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Commenter
                                    </button>
                                </div>
                            </form>

                            @if ($comments->isEmpty())
                                <p class="text-sm text-gray-500 italic">Aucun commentaire</p>
                            @else
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        @foreach ($comments as $comment)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if (!$loop->last)
                                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $comment->user->name }}">
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div>
                                                                <div class="text-sm">
                                                                    <a href="#" class="font-medium text-gray-900">{{ $comment->user->name }}</a>
                                                                </div>
                                                                <p class="mt-0.5 text-sm text-gray-500">
                                                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                                                </p>
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-700">
                                                                <p>{!! nl2br(e($comment->content)) !!}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Informations latérales -->
                    <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Actions</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6 space-y-4">
                            <form action="{{ route('tasks.status.update', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Changer le statut</label>
                                <div class="flex">
                                    <select id="status" name="status" class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="to_do" {{ $task->status === 'to_do' ? 'selected' : '' }}>À faire</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>En révision</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                                    </select>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Mettre à jour
                                    </button>
                                </div>
                            </form>

                            <div>
                                <a href="{{ route('projects.kanban', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full justify-center">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                    Voir le tableau Kanban
                                </a>
                            </div>

                            <div>
                                <a href="{{ route('projects.gantt', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full justify-center">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Voir le diagramme de Gantt
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pièces jointes -->
                    <div class="mt-4 bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Pièces jointes</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <form action="{{ route('tasks.attachments.upload', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                                @csrf
                                <div>
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un fichier</label>
                                    <input type="file" id="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Télécharger
                                    </button>
                                </div>
                            </form>

                            @if ($attachments->isEmpty())
                                <p class="text-sm text-gray-500 italic">Aucune pièce jointe</p>
                            @else
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($attachments as $attachment)
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <div>
                                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                        {{ $attachment->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">
                                                        {{ round($attachment->file_size / 1024, 2) }} KB - Ajouté par {{ $attachment->user->name }}
                                                    </p>
                                                </div>
                                            </div>
                                            <form action="{{ route('tasks.attachments.delete', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id, 'attachment' => $attachment->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?')">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
