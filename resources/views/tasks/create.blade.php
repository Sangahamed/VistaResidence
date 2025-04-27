@extends('layouts.app')

@section('title', 'Créer une tâche')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Créer une nouvelle tâche</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Remplissez les informations pour créer une nouvelle tâche pour le projet {{ $project->name }}.</p>
        </div>
        <div class="border-t border-gray-200">
            <form action="{{ route('tasks.store', ['company' => $company->id, 'project' => $project->id]) }}" method="POST">
                @csrf
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom de la tâche</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="status" name="status" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="to_do" {{ old('status') == 'to_do' ? 'selected' : '' }}>À faire</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>En révision</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="assignee_id" class="block text-sm font-medium text-gray-700">Assigné à</label>
                            <select id="assignee_id" name="assignee_id" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionnez un assigné</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assignee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assignee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="team_id" class="block text-sm font-medium text-gray-700">Équipe</label>
                            <select id="team_id" name="team_id" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionnez une équipe</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('team_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="estimated_hours" class="block text-sm font-medium text-gray-700">Temps estimé (heures)</label>
                            <input type="number" name="estimated_hours" id="estimated_hours" value="{{ old('estimated_hours') }}" step="0.5" min="0" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('estimated_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                            <select id="priority" name="priority" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="subtasks" class="block text-sm font-medium text-gray-700">Sous-tâches (une par ligne)</label>
                            <textarea id="subtasks" name="subtasks" rows="3" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Entrez une sous-tâche par ligne">{{ old('subtasks') }}</textarea>
                            @error('subtasks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('tasks.index', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        Créer la tâche
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
