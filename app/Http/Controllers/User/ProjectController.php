<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company.access')->except(['index', 'show']);
    }


    public function index(Company $company)
    {
        $projects = $company->projects()->paginate(10);
        return view('projects.index', compact('company', 'projects'));
    }
    

    public function create(Request $request)
    {
        $company = $request->company;
        $users = $company->users;
        $teams = $company->teams;
        
        return view('projects.create', compact('company', 'users', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'exists:teams,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $company = $request->company;

        $project = Project::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'company_id' => $company->id,
            'manager_id' => $request->manager_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        // Associer les équipes
        if ($request->has('team_ids')) {
            $project->teams()->attach($request->team_ids);
        }

        // Associer les utilisateurs
        if ($request->has('user_ids')) {
            $project->users()->attach($request->user_ids, ['role' => 'member']);
        }

        // Ajouter le manager comme membre s'il n'est pas déjà inclus
        if ($request->manager_id && !in_array($request->manager_id, $request->user_ids ?? [])) {
            $project->users()->attach($request->manager_id, ['role' => 'manager']);
        }

        return redirect()->route('projects.show', ['company' => $company->id, 'project' => $project->id])
            ->with('success', 'Projet créé avec succès.');
    }

    public function show(Company $company, Project $project)
    {
        $tasks = $project->tasks()->orderBy('status')->orderBy('priority')->orderBy('due_date')->get();
        $members = $project->users;
        $teams = $project->teams;
        
        return view('projects.show', compact('company', 'project', 'tasks', 'members', 'teams'));
    }

    public function edit(Company $company, Project $project)
    {
        $users = $company->users;
        $teams = $company->teams;
        $projectUsers = $project->users->pluck('id')->toArray();
        $projectTeams = $project->teams->pluck('id')->toArray();
        
        return view('projects.edit', compact('company', 'project', 'users', 'teams', 'projectUsers', 'projectTeams'));
    }

    public function update(Request $request, Company $company, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'exists:teams,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'manager_id' => $request->manager_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        // Mettre à jour les équipes
        $project->teams()->sync($request->team_ids ?? []);

        // Mettre à jour les utilisateurs
        $project->users()->detach();
        
        if ($request->has('user_ids')) {
            $project->users()->attach($request->user_ids, ['role' => 'member']);
        }

        // Ajouter le manager comme membre s'il n'est pas déjà inclus
        if ($request->manager_id && !in_array($request->manager_id, $request->user_ids ?? [])) {
            $project->users()->attach($request->manager_id, ['role' => 'manager']);
        }

        return redirect()->route('projects.show', ['company' => $company->id, 'project' => $project->id])
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Company $company, Project $project)
    {
        $project->delete();
        
        return redirect()->route('projects.index', ['company' => $company->id])
            ->with('success', 'Projet supprimé avec succès.');
    }

    public function kanban(Company $company, Project $project)
    {
        $tasks = $project->tasks;
        $todoTasks = $tasks->where('status', 'to_do');
        $inProgressTasks = $tasks->where('status', 'in_progress');
        $reviewTasks = $tasks->where('status', 'review');
        $completedTasks = $tasks->where('status', 'completed');
        
        return view('projects.kanban', compact('company', 'project', 'todoTasks', 'inProgressTasks', 'reviewTasks', 'completedTasks'));
    }

    public function gantt(Company $company, Project $project)
    {
        $tasks = $project->tasks;
        
        // Préparer les données pour le diagramme de Gantt
        $ganttData = [];
        
        foreach ($tasks as $task) {
            $ganttData[] = [
                'id' => $task->id,
                'name' => $task->name,
                'start' => $task->start_date ? $task->start_date->format('Y-m-d') : null,
                'end' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'progress' => $task->isCompleted() ? 100 : ($task->subtasks_progress ?? 0),
                'dependencies' => [], // À implémenter si vous avez des dépendances entre tâches
            ];
        }
        
        return view('projects.gantt', compact('company', 'project', 'ganttData'));
    }
}
