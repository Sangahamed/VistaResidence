<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\Company;
use App\Models\Subtask;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Company $company, Project $project)
{
    $tasks = $project->tasks()->paginate(10);
    return view('tasks.index', compact('company', 'project', 'tasks'));
}

    

    public function create(Company $company, Project $project)
    {
        $users = $project->users;
        $teams = $project->teams;
        
        return view('tasks.create', compact('company', 'project', 'users', 'teams'));
    }

    public function store(Request $request, Company $company, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:to_do,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_hours' => 'nullable|integer|min:0',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'string|max:255',
        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'project_id' => $project->id,
            'created_by' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'team_id' => $request->team_id,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'estimated_hours' => $request->estimated_hours,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        // Créer les sous-tâches
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtaskName) {
                if (!empty($subtaskName)) {
                    Subtask::create([
                        'name' => $subtaskName,
                        'task_id' => $task->id,
                    ]);
                }
            }
        }

        // Mettre à jour le progrès du projet
        $project->updateProgress();

        return redirect()->route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id])
            ->with('success', 'Tâche créée avec succès.');
    }

    public function show(Company $company, Project $project, Task $task)
    {
        $subtasks = $task->subtasks;
        $comments = $task->comments()->with('user')->orderBy('created_at', 'desc')->get();
        $attachments = $task->attachments;
        
        return view('tasks.show', compact('company', 'project', 'task', 'subtasks', 'comments', 'attachments'));
    }

    public function edit(Company $company, Project $project, Task $task)
    {
        $users = $project->users;
        $teams = $project->teams;
        $subtasks = $task->subtasks;
        
        return view('tasks.edit', compact('company', 'project', 'task', 'users', 'teams', 'subtasks'));
    }

    public function update(Request $request, Company $company, Project $project, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:to_do,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
        ]);

        $wasCompleted = $task->isCompleted();
        $isNowCompleted = $request->status === 'completed';

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'team_id' => $request->team_id,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'estimated_hours' => $request->estimated_hours,
            'actual_hours' => $request->actual_hours,
            'completed_at' => $isNowCompleted && !$wasCompleted ? now() : ($wasCompleted && !$isNowCompleted ? null : $task->completed_at),
        ]);

        // Mettre à jour le progrès du projet
        $project->updateProgress();

        return redirect()->route('tasks.show', ['company' => $company->id, 'project' => $project->id, 'task' => $task->id])
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Company $company, Project $project, Task $task)
    {
        $task->delete();
        
        // Mettre à jour le progrès du projet
        $project->updateProgress();
        
        return redirect()->route('tasks.index', ['company' => $company->id, 'project' => $project->id])
            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function addSubtask(Request $request, Company $company, Project $project, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Subtask::create([
            'name' => $request->name,
            'task_id' => $task->id,
        ]);

        return back()->with('success', 'Sous-tâche ajoutée avec succès.');
    }

    public function toggleSubtask(Request $request, Company $company, Project $project, Task $task, Subtask $subtask)
    {
        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        // Mettre à jour le progrès du projet
        $project->updateProgress();

        return back()->with('success', 'Sous-tâche mise à jour avec succès.');
    }

    public function addComment(Request $request, Company $company, Project $project, Task $task)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        TaskComment::create([
            'content' => $request->content,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    public function uploadAttachment(Request $request, Company $company, Project $project, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('task-attachments/' . $task->id, 'public');

        TaskAttachment::create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Pièce jointe ajoutée avec succès.');
    }

    public function deleteAttachment(Company $company, Project $project, Task $task, TaskAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Pièce jointe supprimée avec succès.');
    }

    public function updateStatus(Request $request, Company $company, Project $project, Task $task)
    {
        $request->validate([
            'status' => 'required|in:to_do,in_progress,review,completed',
        ]);

        $wasCompleted = $task->isCompleted();
        $isNowCompleted = $request->status === 'completed';

        $task->update([
            'status' => $request->status,
            'completed_at' => $isNowCompleted && !$wasCompleted ? now() : ($wasCompleted && !$isNowCompleted ? null : $task->completed_at),
        ]);

        // Mettre à jour le progrès du projet
        $project->updateProgress();

        return back()->with('success', 'Statut de la tâche mis à jour avec succès.');
    }
}
