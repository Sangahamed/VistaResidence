<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Chatify\Facades\ChatifyMessenger as Chatify;


class PropertyMessageController extends Controller
{
    /**
     * Initialiser une conversation à propos d'une propriété.
     */

  public function startConversation(Request $request, Property $property)
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        try {
            // Récupérer le destinataire via la relation "owner" du modèle Property
            $recipient = $property->owner;

            // Si la propriété appartient à une entreprise, tenter de récupérer l'agent
            if ($property->company_id) {
                $company = Company::find($property->company_id);
                if ($company) {
                    // Ici, on cherche le premier utilisateur (agent) de l'entreprise
                    $agent = $company->users()->first();
                    if ($agent) {
                        $recipient = $agent;
                    }
                }
            }

            // Vérifier que le destinataire est valide et que l'utilisateur ne s'envoie pas un message à lui-même
            if (!$recipient || $recipient->id === Auth::id()) {
                return back()->with('error', 'Destinataire invalide.');
            }

            // Construire le contenu du message en lien avec la propriété
            $messageContent = "Intérêt pour la propriété : " . $property->title
                . ($request->message ? "\n\n" . $request->message : "");

            // Envoyer le message : Chatify crée automatiquement une conversation si nécessaire
            Chatify::newMessage([
                'from_id'    => Auth::id(),
                'to_id'      => $recipient->id,
                'body'       => $messageContent,
                'attachment' => null,
            ]);

            // Note : la méthode fetchMessages() n'est plus disponible, donc on ne l'appelle pas.

            return redirect()->route('messenger', ['id' => $recipient->id])
                ->with('success', 'Message envoyé avec succès.');
        } catch (\Exception $e) {
            \Log::error("Chatify Error: " . $e->getMessage());
            return back()->with('error', 'Erreur technique: ' . $e->getMessage());
        }
    }

    
    /**
     * Afficher la liste des agents immobiliers disponibles pour discuter.
     */
    public function showAgents()
    {
        // Récupérer tous les utilisateurs qui sont des agents immobiliers ou des entreprises
        $agents = User::where('account_type', 'company')
            ->orWhereHas('roles', function($query) {
                $query->where('name', 'like', 'agent%');
            })
            ->with('roles')
            ->get();
        
        return view('messages.agents', compact('agents'));
    }
}