<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Chatify\Facades\ChatifyMessenger as Chatify;

class PropertyMessageController extends Controller
{
    /**
     * Initialiser une conversation à propos d'une propriété.
     */
    public function startConversation(Request $request, Property $property)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour envoyer un message.');
        }
        
        // Déterminer le destinataire (propriétaire ou agent de la propriété)
        $recipient = $property->user;
        
        // Si la propriété appartient à une entreprise, trouver un agent de cette entreprise
        if ($property->company_id) {
            $company = Company::find($property->company_id);
            if ($company) {
                // Trouver un agent de l'entreprise (premier utilisateur trouvé)
                $agent = $company->users()->first();
                if ($agent) {
                    $recipient = $agent;
                }
            }
        }
        
        // Vérifier que le destinataire existe
        if (!$recipient) {
            return redirect()->back()->with('error', 'Impossible de trouver un destinataire pour cette propriété.');
        }
        
        // Vérifier que l'utilisateur n'essaie pas de s'envoyer un message à lui-même
        if ($recipient->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous envoyer un message à vous-même.');
        }
        
        // Créer ou récupérer la conversation
        $conversation = Chatify::getConversation([
            'from_id' => Auth::id(),
            'to_id' => $recipient->id,
        ]);
        
        // Préparer le message initial concernant la propriété
        $messageText = "Bonjour, je suis intéressé(e) par votre propriété : " . $property->title . " (" . $property->reference . ").";
        
        // Si un message personnalisé est fourni, l'ajouter
        if ($request->has('message') && !empty($request->message)) {
            $messageText .= "\n\n" . $request->message;
        }
        
        // Envoyer le message initial si c'est une nouvelle conversation
        if (!$conversation) {
            Chatify::newMessage([
                'from_id' => Auth::id(),
                'to_id' => $recipient->id,
                'body' => $messageText,
            ]);
        }
        
        // Rediriger vers la conversation
        return redirect()->route('chatify')->with([
            'message' => 'Conversation initiée avec succès.',
            'type' => 'success',
            'user_id' => $recipient->id
        ]);
    }
    
    /**
     * Afficher la liste des agents immobiliers disponibles pour discuter.
     */
    public function showAgents()
    {
        // Récupérer tous les utilisateurs qui sont des agents immobiliers ou des entreprises
        $agents = User::where('account_type', 'company')
            ->orWhereHas('roles', function($query) {
                $query->where('slug', 'like', 'agent-%');
            })
            ->get();
        
        return view('messages.agents', compact('agents'));
    }
    
    /**
     * Afficher les conversations liées aux propriétés.
     */
    public function propertyConversations()
    {
        $user = Auth::user();
        
        // Si l'utilisateur est un client, récupérer ses conversations avec des agents
        if ($user->account_type === 'client') {
            $conversations = Chatify::getConversations([
                'from_id' => $user->id,
                'type' => 'user',
            ]);
        } 
        // Si l'utilisateur est un agent ou une entreprise, récupérer ses conversations avec des clients
        else {
            $conversations = Chatify::getConversations([
                'to_id' => $user->id,
                'type' => 'user',
            ]);
        }
        
        return view('messages.property-conversations', compact('conversations'));
    }
}
