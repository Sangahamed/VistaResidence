@extends('layouts.app')

@section('title', 'Tableau Kanban')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tableau Kanban - {{ $project->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez visuellement les tâches du projet.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au projet
                </a>
                <a href="{{ route('tasks.create', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle tâche
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div id="kanban-board" data-company-id="{{ $company->id }}" data-project-id="{{ $project->id }}" class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Colonne À faire -->
                <div class="kanban-column bg-gray-50 rounded-lg shadow" data-status="to_do">
                    <div class="p-3 bg-gray-100 rounded-t-lg border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                            À faire
                            <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-xs">{{ $todoTasks->count() }}</span>
                        </h3>
                    </div>
                    <div class="kanban-tasks p-2 min-h-[200px]">
                        @foreach ($todoTasks as $task)
                            <div class="task-card bg-white p-3 rounded shadow mb-2 cursor-move" data-task-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->name }}</h4>
                                    <span class="priority-badge {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} text-xs px-2 py-0.5 rounded-full">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if ($task->description)
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if ($task->assignee)
                                            <img class="h-6 w-6 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                            <span class="ml-1 text-xs text-gray-500">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    @if ($task->due_date)
                                        <span class="text-xs {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($task->subtasks->count() > 0)
                                    <div class="mt-2 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="ml-1 text-xs text-gray-500">{{ $task->completed_subtasks_count }}/{{ $task->total_subtasks_count }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne En cours -->
                <div class="kanban-column bg-gray-50 rounded-lg shadow" data-status="in_progress">
                    <div class="p-3 bg-blue-100 rounded-t-lg border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            En cours
                            <span class="ml-2 bg-blue-200 text-blue-800 py-0.5 px-2 rounded-full text-xs">{{ $inProgressTasks->count() }}</span>
                        </h3>
                    </div>
                    <div class="kanban-tasks p-2 min-h-[200px]">
                        @foreach ($inProgressTasks as $task)
                            <div class="task-card bg-white p-3 rounded shadow mb-2 cursor-move" data-task-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->name }}</h4>
                                    <span class="priority-badge {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} text-xs px-2 py-0.5 rounded-full">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if ($task->description)
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if ($task->assignee)
                                            <img class="h-6 w-6 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                            <span class="ml-1 text-xs text-gray-500">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    @if ($task->due_date)
                                        <span class="text-xs {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($task->subtasks->count() > 0)
                                    <div class="mt-2 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="ml-1 text-xs text-gray-500">{{ $task->completed_subtasks_count }}/{{ $task->total_subtasks_count }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne En révision -->
                <div class="kanban-column bg-gray-50 rounded-lg shadow" data-status="review">
                    <div class="p-3 bg-purple-100 rounded-t-lg border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                            En révision
                            <span class="ml-2 bg-purple-200 text-purple-800 py-0.5 px-2 rounded-full text-xs">{{ $reviewTasks->count() }}</span>
                        </h3>
                    </div>
                    <div class="kanban-tasks p-2 min-h-[200px]">
                        @foreach ($reviewTasks as $task)
                            <div class="task-card bg-white p-3 rounded shadow mb-2 cursor-move" data-task-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->name }}</h4>
                                    <span class="priority-badge {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} text-xs px-2 py-0.5 rounded-full">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if ($task->description)
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if ($task->assignee)
                                            <img class="h-6 w-6 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                            <span class="ml-1 text-xs text-gray-500">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    @if ($task->due_date)
                                        <span class="text-xs {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($task->subtasks->count() > 0)
                                    <div class="mt-2 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="ml-1 text-xs text-gray-500">{{ $task->completed_subtasks_count }}/{{ $task->total_subtasks_count }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne Terminé -->
                <div class="kanban-column bg-gray-50 rounded-lg shadow" data-status="completed">
                    <div class="p-3 bg-green-100 rounded-t-lg border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Terminé
                            <span class="ml-2 bg-green-200 text-green-800 py-0.5 px-2 rounded-full text-xs">{{ $completedTasks->count() }}</span>
                        </h3>
                    </div>
                    <div class="kanban-tasks p-2 min-h-[200px]">
                        @foreach ($completedTasks as $task)
                            <div class="task-card bg-white p-3 rounded shadow mb-2 cursor-move" data-task-id="{{ $task->id }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->name }}</h4>
                                    <span class="priority-badge {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} text-xs px-2 py-0.5 rounded-full">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if ($task->description)
                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if ($task->assignee)
                                            <img class="h-6 w-6 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $task->assignee->name }}">
                                            <span class="ml-1 text-xs text-gray-500">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    @if ($task->completed_at)
                                        <span class="text-xs text-green-600">
                                            {{ $task->completed_at->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($task->subtasks->count() > 0)
                                    <div class="mt-2 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="ml-1 text-xs text-gray-500">{{ $task->completed_subtasks_count }}/{{ $task->total_subtasks_count }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/kanban.js') }}"></script>
    <style>
        .task-ghost {
            opacity: 0.5;
            background: #f3f4f6;
            border: 1px dashed #9ca3af;
        }
        .task-chosen {
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
        .task-drag {
            transform: rotate(2deg);
        }
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 4px;
            color: white;
            z-index: 1000;
            animation: fadeIn 0.3s ease-in-out;
        }
        .notification.success {
            background-color: #10b981;
        }
        .notification.error {
            background-color: #ef4444;
        }
        .notification.fade-out {
            animation: fadeOut 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(10px); }
        }
    </style>
@endpush
