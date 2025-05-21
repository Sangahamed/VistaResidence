<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class VisitStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visit;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(PropertyVisit $visit, string $oldStatus, string $newStatus)
    {
        $this->visit = $visit;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Récupérer les préférences de notification de l'utilisateur
        $channels = ['database', 'broadcast', 'mail'];
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_visit_status) {
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
        $statusLabels = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            'rescheduled' => 'Reprogrammée',
        ];
        
        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;
        
        return (new MailMessage)
            ->subject('Statut de visite modifié pour ' . $this->visit->property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut d\'une visite a été modifié.')
            ->line('Propriété : ' . $this->visit->property->title)
            ->line('Date prévue : ' . $this->visit->scheduled_at->format('d/m/Y à H:i'))
            ->line('Ancien statut : ' . $oldStatusLabel)
            ->line('Nouveau statut : ' . $newStatusLabel)
            ->action('Voir la visite', $url)
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'type' => 'visit_status_changed',
            'message' => 'Statut de visite modifié pour ' . $this->visit->property->title,
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'type' => 'visit_status_changed',
            'message' => 'Statut de visite modifié pour ' . $this->visit->property->title,
            'time' => now()->diffForHumans(),
        ]);
    }
}