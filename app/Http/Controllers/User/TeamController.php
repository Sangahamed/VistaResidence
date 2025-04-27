<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitation;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company.access')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $company = $request->company;
        $teams = $company->teams;
        
        return view('teams.index', compact('teams', 'company'));
    }

    public function create(Request $request)
    {
        $company = $request->company;
        $users = $company->users;
        
        return view('teams.create', compact('company', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $company = $request->company;

        $team = Team::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'company_id' => $company->id,
            'leader_id' => $request->leader_id,
        ]);

        if ($request->has('members')) {
            $team->users()->attach($request->members, ['role' => 'member']);
        }

        // Ajouter le leader comme membre s'il n'est pas déjà inclus
        if ($request->leader_id && !in_array($request->leader_id, $request->members ?? [])) {
            $team->users()->attach($request->leader_id, ['role' => 'leader']);
        }

        return redirect()->route('teams.show', ['company' => $company->id, 'team' => $team->id])
            ->with('success', 'Équipe créée avec succès.');
    }

    public function show(Company $company, Team $team)
    {
        $members = $team->users;
        $projects = $team->projects;
        $tasks = $team->tasks;
        
        return view('teams.show', compact('company', 'team', 'members', 'projects', 'tasks'));
    }

    public function edit(Company $company, Team $team)
    {
        $users = $company->users;
        $members = $team->users->pluck('id')->toArray();
        
        return view('teams.edit', compact('company', 'team', 'users', 'members'));
    }

    public function update(Request $request, Company $company, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        // Mettre à jour les membres
        $team->users()->detach();
        
        if ($request->has('members')) {
            $team->users()->attach($request->members, ['role' => 'member']);
        }

        // Ajouter le leader comme membre s'il n'est pas déjà inclus
        if ($request->leader_id && !in_array($request->leader_id, $request->members ?? [])) {
            $team->users()->attach($request->leader_id, ['role' => 'leader']);
        }

        return redirect()->route('teams.show', ['company' => $company->id, 'team' => $team->id])
            ->with('success', 'Équipe mise à jour avec succès.');
    }

    public function destroy(Company $company, Team $team)
    {
        $team->delete();
        
        return redirect()->route('teams.index', ['company' => $company->id])
            ->with('success', 'Équipe supprimée avec succès.');
    }

    public function invite(Request $request, Company $company, Team $team)
    {
        $request->validate([
            'email' => 'required|email',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        // Vérifier si l'utilisateur existe déjà
        $user = User::where('email', $request->email)->first();
        
        if ($user && $team->users->contains($user->id)) {
            return back()->with('error', 'Cet utilisateur est déjà membre de l\'équipe.');
        }

        // Créer une invitation
        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => Str::random(32),
            'company_id' => $company->id,
            'team_id' => $team->id,
            'role_id' => $request->role_id,
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        // Envoyer l'email d'invitation
        Mail::to($request->email)->send(new TeamInvitation($invitation));

        return back()->with('success', 'Invitation envoyée avec succès.');
    }
}
