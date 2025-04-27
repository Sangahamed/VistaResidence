<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewChatMessage;
use Chatify\Events\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatMessageNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NewMessage $event)
    {
        // Récupérer les données du message
        $message = $event->message;
        
        // Récupérer l'expéditeur et le destinataire
        $sender = User::find($message->from_id);
        $recipient = User::find($message->to_id);
        
        // Vérifier que le destinataire existe
        if (!$recipient) {
            return;
        }
        
        // Envoyer la notification au destinataire
        $recipient->notify(new NewChatMessage($message->body, $sender));
    }
}