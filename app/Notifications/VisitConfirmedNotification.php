<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitConfirmedNotification extends Notification implements ShouldQueue
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
    // public function via($notifiable)
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
        $agent = $this->visit->agent;
        
        return (new MailMessage)
            ->subject('Visite confirmée - ' . $property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre demande de visite a été confirmée :')
            ->line('**' . $property->title . '** (' . $property->reference . ')')
            ->line('**Date et heure :** ' . $this->visit->visit_date->format('d/m/Y') . ' de ' . $this->visit->visit_time_start . ' à ' . $this->visit->visit_time_end)
            ->line('**Agent immobilier :** ' . $agent->name . ' (' . $agent->email . ')')
            ->line('**Code de confirmation :** ' . $this->visit->confirmation_code)
            ->line('Veuillez présenter ce code lors de votre visite.')
            ->action('Voir les détails', route('visits.show', $this->visit))
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
            'agent_id' => $this->visit->agent_id,
            'agent_name' => $this->visit->agent->name,
            'visit_date' => $this->visit->visit_date->format('Y-m-d'),
            'visit_time' => $this->visit->visit_time_start . ' - ' . $this->visit->visit_time_end,
            'confirmation_code' => $this->visit->confirmation_code,
            'type' => 'visit_confirmed',
            'title' => 'Visite Confirmer : ' . $this->visit->property->title,  // <-- AJOUT
            'message' => 'La visite prévue le ' . $this->visit->visit_date->format('d/m/Y') . ' a été confirmee.',
            'url' => route('visits.show', $this->visit),

        ];
    }
}