<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Company;
use App\Notifications\InvitationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['accept', 'register']);
        $this->middleware('signed')->only('accept');
    }

    public function index()
    {
        $this->authorize('manage invitations');

        $invitations = Invitation::with(['role', 'company', 'inviter'])
            ->where('invited_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('invitations.index', compact('invitations'));
    }

    public function create()
    {
        $this->authorize('send invitations');

        $roles = Role::whereNotIn('name', ['super-admin'])->get();
        $companies = auth()->user()->companies;

        return view('invitations.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $this->authorize('send invitations');

        $request->validate([
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        // Vérifier si l'utilisateur a le droit d'inviter pour cette entreprise
        if ($request->company_id) {
            $company = Company::findOrFail($request->company_id);
            $userCompany = auth()->user()->companies()->where('company_id', $company->id)->first();
            
            if (!$userCompany && !auth()->user()->hasRole('super-admin')) {
                return redirect()->back()->with('error', 'Vous n\'avez pas le droit d\'inviter des utilisateurs pour cette entreprise.');
            }
        }

        // Vérifier si l'utilisateur existe déjà
        $existingUser = User::where('email', $request->email)->first();

        // Générer un token et définir la date d'expiration (7 jours)
        $token = Invitation::generateToken();
        $expiresAt = Carbon::now()->addDays(7);

        // Créer l'invitation
        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => $token,
            'role_id' => $request->role_id,
            'company_id' => $request->company_id,
            'invited_by' => auth()->id(),
            'status' => 'pending',
            'expires_at' => $expiresAt,
        ]);

        // Envoyer la notification
        Notification::route('mail', $request->email)
            ->notify(new InvitationNotification($invitation));

        return redirect()->route('invitations.index')
            ->with('success', 'Invitation envoyée avec succès.');
    }

    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Vérifier si l'utilisateur existe déjà
        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            // L'utilisateur existe, on lui attribue le rôle et l'entreprise
            $user->assignRole($invitation->role);
            
            if ($invitation->company) {
                $user->companies()->syncWithoutDetaching([
                    $invitation->company_id => ['role_id' => $invitation->role_id]
                ]);
            }
            
            // Mettre à jour le statut de l'invitation
            $invitation->update(['status' => 'accepted']);
            
            // Connecter l'utilisateur
            auth()->login($user);
            
            return redirect()->route('dashboard')
                ->with('success', 'Invitation acceptée avec succès.');
        }

        // L'utilisateur n'existe pas, on le redirige vers le formulaire d'inscription
        return redirect()->route('invitations.register', ['token' => $token]);
    }

    public function register(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('invitations.register', compact('invitation'));
    }

    public function registerStore(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Déterminer le type de compte en fonction du rôle
        $accountType = 'client'; // Par défaut
        $role = Role::findOrFail($invitation->role_id);
        
        if ($role->name === 'individual') {
            $accountType = 'individual';
        } elseif (in_array($role->name, ['company-admin', 'agent'])) {
            $accountType = 'company';
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'account_type' => $accountType,
            'email_verified_at' => now(), // L'email est vérifié via l'invitation
        ]);

        // Attribuer le rôle
        $user->assignRole($invitation->role);
        
        // Associer à l'entreprise si applicable
        if ($invitation->company) {
            $user->companies()->attach($invitation->company_id, ['role_id' => $invitation->role_id]);
        }
        
        // Mettre à jour le statut de l'invitation
        $invitation->update(['status' => 'accepted']);
        
        // Connecter l'utilisateur
        auth()->login($user);
        
        return redirect()->route('dashboard')
            ->with('success', 'Compte créé avec succès.');
    }

    public function resend(Invitation $invitation)
    {
        $this->authorize('manage invitations');

        // Vérifier si l'invitation appartient à l'utilisateur connecté
        if ($invitation->invited_by !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Vous n\'êtes pas autorisé à renvoyer cette invitation.');
        }

        // Vérifier si l'invitation est en attente
        if ($invitation->status !== 'pending') {
            return redirect()->route('invitations.index')
                ->with('error', 'Seules les invitations en attente peuvent être renvoyées.');
        }

        // Générer un nouveau token et mettre à jour la date d'expiration
        $invitation->update([
            'token' => Invitation::generateToken(),
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Renvoyer la notification
        Notification::route('mail', $invitation->email)
            ->notify(new InvitationNotification($invitation));

        return redirect()->route('invitations.index')
            ->with('success', 'Invitation renvoyée avec succès.');
    }

    public function destroy(Invitation $invitation)
    {
        $this->authorize('manage invitations');

        // Vérifier si l'invitation appartient à l'utilisateur connecté
        if ($invitation->invited_by !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette invitation.');
        }

        $invitation->delete();

        return redirect()->route('invitations.index')
            ->with('success', 'Invitation supprimée avec succès.');
    }
}