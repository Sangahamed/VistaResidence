<?php

namespace App\Notifications;

use App\Models\Property;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PropertyStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Property $property, string $oldStatus, string $newStatus)
    {
        $this->property = $property;
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
        $channels = ['database', 'broadcast'];
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_property_status) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/properties/' . $this->property->id);
        $statusLabels = [
            'draft' => 'Brouillon',
            'pending' => 'En attente',
            'published' => 'Publiée',
            'sold' => 'Vendue',
            'rented' => 'Louée',
            'archived' => 'Archivée',
        ];
        
        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;
        
        return (new MailMessage)
            ->subject('Statut de propriété modifié : ' . $this->property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut d\'une propriété a été modifié.')
            ->line('Propriété : ' . $this->property->title)
            ->line('Ancien statut : ' . $oldStatusLabel)
            ->line('Nouveau statut : ' . $newStatusLabel)
            ->action('Voir la propriété', $url)
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
            'property_id' => $this->property->id,
            'title' => $this->property->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'property_status_changed',
            'message' => 'Statut de propriété modifié : ' . $this->property->title,
        ];
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'property_id' => $this->property->id,
            'title' => $this->property->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'property_status_changed',
            'message' => 'Statut de propriété modifié : ' . $this->property->title,
            'time' => now()->diffForHumans(),
        ]);
    }
}