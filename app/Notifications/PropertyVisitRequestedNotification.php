<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PropertyVisitRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visit;

    /**
     * Create a new notification instance.
     */
    public function __construct(PropertyVisit $visit)
    {
        $this->visit = $visit;
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
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_visit_requested) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/visits/' . $this->visit->id);
        
        return (new MailMessage)
            ->subject('Nouvelle demande de visite pour ' . $this->visit->property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle demande de visite a été créée.')
            ->line('Propriété : ' . $this->visit->property->title)
            ->line('Adresse : ' . $this->visit->property->address)
            ->line('Client : ' . $this->visit->user->name)
            ->line('Email du client : ' . $this->visit->user->email)
            ->line('Date prévue : ' . $this->visit->scheduled_at->format('d/m/Y à H:i'))
            ->action('Voir la demande de visite', $url)
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
            'visit_id' => $this->visit->id,
            'property_id' => $this->visit->property->id,
            'property_title' => $this->visit->property->title,
            'client_name' => $this->visit->user->name,
            'client_email' => $this->visit->user->email,
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'type' => 'visit_requested',
            'message' => 'Nouvelle demande de visite pour ' . $this->visit->property->title,
        ];
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'visit_id' => $this->visit->id,
            'property_id' => $this->visit->property->id,
            'property_title' => $this->visit->property->title,
            'client_name' => $this->visit->user->name,
            'client_email' => $this->visit->user->email,
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'type' => 'visit_requested',
            'message' => 'Nouvelle demande de visite pour ' . $this->visit->property->title,
            'time' => now()->diffForHumans(),
        ]);
    }
}