<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lead;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Récupérer les préférences de notification de l'utilisateur
        $channels = ['database', 'broadcast'];
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_new_lead) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/leads/' . $this->lead->id);
        
        return (new MailMessage)
            ->subject('Nouveau lead : ' . $this->lead->name)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouveau lead a été créé.')
            ->line('Nom : ' . $this->lead->name)
            ->line('Email : ' . $this->lead->email)
            ->line('Téléphone : ' . $this->lead->phone)
            ->line('Source : ' . $this->lead->source)
            ->action('Voir le lead', $url)
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'source' => $this->lead->source,
            'type' => 'new_lead',
            'message' => 'Nouveau lead : ' . $this->lead->name,
        ];
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'lead_id' => $this->lead->id,
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'source' => $this->lead->source,
            'type' => 'new_lead',
            'message' => 'Nouveau lead : ' . $this->lead->name,
            'time' => now()->diffForHumans(),
        ]);
    }
}