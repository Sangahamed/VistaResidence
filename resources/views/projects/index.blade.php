@extends('layouts.app')

@section('title', 'Projets')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Projets de {{ $company->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez les projets de votre entreprise.</p>
            </div>
            <div>
                <a href="{{ route('projects.create', ['company' => $company->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau projet
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            @if ($projects->isEmpty())
                <div class="px-4 py-5 sm:p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer un projet pour votre entreprise.</p>
                    <div class="mt-6">
                        <a href="{{ route('projects.create', ['company' => $company->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouveau projet
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Équipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progression</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-amber-100 rounded-full">
                                                <span class="text-amber-700 font-medium text-lg">{{ substr($project->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $project->status === 'not_started' ? 'bg-gray-100 text-gray-800' : 
                                              ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                              ($project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ $project->status === 'not_started' ? 'Non démarré' : 
                                              ($project->status === 'in_progress' ? 'En cours' : 
                                              ($project->status === 'on_hold' ? 'En pause' : 'Terminé')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $project->team ? $project->team->name : 'Non assigné' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($project->manager)
                                            <div class="flex items-center">
                                                <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($project->manager->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $project->manager->name }}">
                                                <div class="text-sm text-gray-900">{{ $project->manager->name }}</div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}
                                            -
                                            {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}
                                        </div>
                                        @if ($project->end_date && $project->end_date->isPast() && $project->status !== 'completed')
                                            <div class="text-xs text-red-600 font-medium">En retard</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-amber-600 h-2.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-900">{{ $project->progress }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="text-amber-600 hover:text-amber-900 mr-3">Voir</a>
                                        <a href="{{ route('projects.edit', ['company' => $company->id, 'project' => $project->id]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                                        <form action="{{ route('projects.destroy', ['company' => $company->id, 'project' => $project->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
