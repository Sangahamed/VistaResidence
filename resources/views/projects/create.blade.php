@extends('layouts.app')

@section('title', 'Créer un projet')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Créer un nouveau projet</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Remplissez les informations pour créer un nouveau projet pour {{ $company->name }}.</p>
        </div>
        <div class="border-t border-gray-200">
            <form action="{{ route('projects.store', ['company' => $company->id]) }}" method="POST">
                @csrf
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom du projet</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="status" name="status" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="not_started" {{ old('status') == 'not_started' ? 'selected' : '' }}>Non démarré</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>En pause</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="team_id" class="block text-sm font-medium text-gray-700">Équipe</label>
                            <select id="team_id" name="team_id" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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
                            <label for="manager_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                            <select id="manager_id" name="manager_id" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionnez un responsable</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="budget" class="block text-sm font-medium text-gray-700">Budget</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" class="focus:ring-amber-500 focus:border-amber-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" step="0.01">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">EUR</span>
                                </div>
                            </div>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                            <select id="priority" name="priority" class="mt-1 focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Membres du projet</label>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($users as $user)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="user_{{ $user->id }}" name="users[]" type="checkbox" value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'checked' : '' }} class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="user_{{ $user->id }}" class="font-medium text-gray-700">{{ $user->name }}</label>
                                            <p class="text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('users')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('projects.index', ['company' => $company->id]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        Créer le projet
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
