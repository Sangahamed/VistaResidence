<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public $visit;
    //  public $afterCommit = true;

    public function __construct(PropertyVisit $visit)
    {
        $this->visit = $visit;
    }

    // public function via($notifiable)
    // {
    //     return $notifiable->notificationPreference->getNotificationChannels();
    // }

     public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Nouvelle demande de visite")
            ->line("Une nouvelle visite a été demandée pour votre propriété:")
            ->line("Propriété: {$this->visit->property->title}")
            ->line("Date: {$this->visit->visit_date->format('d/m/Y H:i')}")
            ->action('Voir les détails', route('visits.show', $this->visit));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'visit.requested',
            'visit_id' => $this->visit->id,
            'property_id' => $this->visit->property_id,
            'title' => "Nouvelle demande de visite",
            'message' => "Visite demandée pour {$this->visit->property->title}",
            'url' => route('visits.show', $this->visit),
            'date' => $this->visit->visit_date->toIso8601String()

        ];
    }
}