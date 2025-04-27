<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitation;
use Illuminate\Support\Str;

class TeamMemberController extends Controller
{
    /**
     * Affiche la liste des membres d'une équipe
     */
    public function index(Team $team)
    {
        $this->authorize('view', $team);
        
        $members = $team->users()->paginate(15);
        
        return view('teams.members.index', compact('team', 'members'));
    }
    
    /**
     * Affiche le formulaire pour ajouter un membre à l'équipe
     */
    public function create(Team $team)
    {
        $this->authorize('update', $team);
        
        $users = User::whereDoesntHave('teams', function($query) use ($team) {
            $query->where('team_id', $team->id);
        })->get();
        
        return view('teams.members.create', compact('team', 'users'));
    }
    
    /**
     * Ajoute un membre à l'équipe
     */
    public function store(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:255',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        
        $team->users()->attach($user->id, [
            'role' => $validated['role']
        ]);
        
        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Membre ajouté à l\'équipe avec succès.');
    }
    
    /**
     * Affiche le formulaire pour modifier le rôle d'un membre
     */
    public function edit(Team $team, User $member)
    {
        $this->authorize('update', $team);
        
        $role = $team->users()->where('user_id', $member->id)->first()->pivot->role;
        
        return view('teams.members.edit', compact('team', 'member', 'role'));
    }
    
    /**
     * Met à jour le rôle d'un membre
     */
    public function update(Request $request, Team $team, User $member)
    {
        $this->authorize('update', $team);
        
        $validated = $request->validate([
            'role' => 'required|string|max:255',
        ]);
        
        $team->users()->updateExistingPivot($member->id, [
            'role' => $validated['role']
        ]);
        
        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Rôle du membre mis à jour avec succès.');
    }
    
    /**
     * Retire un membre de l'équipe
     */
    public function destroy(Team $team, User $member)
    {
        $this->authorize('update', $team);
        
        // Empêcher la suppression du leader de l'équipe
        if ($team->leader_id === $member->id) {
            return redirect()->route('teams.members.index', $team)
                ->with('error', 'Vous ne pouvez pas retirer le leader de l\'équipe.');
        }
        
        $team->users()->detach($member->id);
        
        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Membre retiré de l\'équipe avec succès.');
    }
    
    /**
     * Invite un utilisateur à rejoindre l'équipe
     */
    public function invite(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        
        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|string|max:255',
        ]);
        
        // Vérifier si l'utilisateur existe déjà
        $user = User::where('email', $validated['email'])->first();
        
        if ($user) {
            // Si l'utilisateur est déjà dans l'équipe
            if ($team->users()->where('user_id', $user->id)->exists()) {
                return redirect()->route('teams.members.index', $team)
                    ->with('error', 'Cet utilisateur est déjà membre de l\'équipe.');
            }
            
            // Ajouter l'utilisateur existant à l'équipe
            $team->users()->attach($user->id, [
                'role' => $validated['role']
            ]);
            
            return redirect()->route('teams.members.index', $team)
                ->with('success', 'Utilisateur existant ajouté à l\'équipe avec succès.');
        }
        
        // Créer une invitation pour un nouvel utilisateur
        $invitation = $team->invitations()->create([
            'email' => $validated['email'],
            'token' => Str::uuid(),
            'role' => $validated['role'],
            'expires_at' => now()->addDays(7),
        ]);
        
        // Envoyer l'email d'invitation
        Mail::to($validated['email'])->send(new TeamInvitation($invitation));
        
        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Invitation envoyée avec succès.');
    }
}
