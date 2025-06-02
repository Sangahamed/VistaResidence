<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitCancelledNotification extends Notification implements ShouldQueue
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
     */

    //  public function via($notifiable)
    // {
    //     return $notifiable->notificationPreference->getNotificationChannels();
    // }
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $property = $this->visit->property;
        $cancelledBy = User::find($this->visit->cancelled_by);
        
        return (new MailMessage)
            ->subject('Visite annulée - ' . $property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une visite a été annulée :')
            ->line('**' . $property->title . '** (' . $property->reference . ')')
            ->line('**Date et heure :** ' . $this->visit->visit_date->format('d/m/Y') . ' de ' . $this->visit->visit_time_start . ' à ' . $this->visit->visit_time_end)
            ->line('**Annulée par :** ' . $cancelledBy->name)
            ->line('**Raison de l\'annulation :** ' . $this->visit->cancellation_reason)
            ->action('Voir les détails', route($notifiable->hasRole('agent') ? 'agent.visits.show' : 'visits.show', $this->visit))
            ->line('Merci d\'utiliser notre plateforme !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'visit_id' => $this->visit->id,
            'property_id' => $this->visit->property_id,
            'property_title' => $this->visit->property->title,
            'cancelled_by' => $this->visit->cancelled_by,
            'cancellation_reason' => $this->visit->cancellation_reason,
            'visit_date' => $this->visit->visit_date->format('Y-m-d'),
            'visit_time' => $this->visit->visit_time_start . ' - ' . $this->visit->visit_time_end,
            'type' => 'visit_cancelled',
            'title' => 'Visite annulée : ' . $this->visit->property->title,  // <-- AJOUT
            'message' => 'La visite prévue le ' . $this->visit->visit_date->format('d/m/Y') . ' a été annulée.',
            'url' => route('visits.show', $this->visit),

        ];
    }

}