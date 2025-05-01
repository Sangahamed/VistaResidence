<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visit;
    protected $recipientType;

    /**
     * Create a new notification instance.
     */
    public function __construct(PropertyVisit $visit, string $recipientType)
    {
        $this->visit = $visit;
        $this->recipientType = $recipientType; // 'client' ou 'agent'
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/visits/' . $this->visit->id);
        
        if ($this->recipientType === 'client') {
            return (new MailMessage)
                ->subject('Rappel de visite pour demain : ' . $this->visit->property->title)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Nous vous rappelons que vous avez une visite prévue demain.')
                ->line('Propriété : ' . $this->visit->property->title)
                ->line('Adresse : ' . $this->visit->property->address)
                ->line('Date et heure : ' . $this->visit->scheduled_at->format('d/m/Y à H:i'))
                ->line('Agent : ' . ($this->visit->agent ? $this->visit->agent->user->name : 'Non assigné'))
                ->action('Voir les détails de la visite', $url)
                ->line('Merci d\'utiliser notre application !');
        } else {
            return (new MailMessage)
                ->subject('Rappel de visite pour demain : ' . $this->visit->property->title)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Nous vous rappelons que vous avez une visite à effectuer demain.')
                ->line('Propriété : ' . $this->visit->property->title)
                ->line('Adresse : ' . $this->visit->property->address)
                ->line('Date et heure : ' . $this->visit->scheduled_at->format('d/m/Y à H:i'))
                ->line('Client : ' . $this->visit->user->name)
                ->line('Téléphone du client : ' . ($this->visit->user->phone ?? 'Non renseigné'))
                ->action('Voir les détails de la visite', $url)
                ->line('Merci d\'utiliser notre application !');
        }
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
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'type' => 'visit_reminder',
            'recipient_type' => $this->recipientType,
            'message' => 'Rappel de visite pour demain : ' . $this->visit->property->title,
        ];
    }
}