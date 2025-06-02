<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPropertyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;

    /**
     * Create a new notification instance.
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
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
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_new_property) {
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
        
        return (new MailMessage)
            ->subject('Nouvelle propriété ajoutée : ' . $this->property->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle propriété a été ajoutée à votre agence.')
            ->line('Titre : ' . $this->property->title)
            ->line('Adresse : ' . $this->property->address)
            ->line('Prix : ' . number_format($this->property->price, 0, ',', ' ') . ' €')
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
            'address' => $this->property->address,
            'price' => $this->property->price,
            'type' => 'new_property',
            'message' => 'Nouvelle propriété ajoutée : ' . $this->property->title,
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
            'address' => $this->property->address,
            'price' => $this->property->price,
            'type' => 'new_property',
            'message' => 'Nouvelle propriété ajoutée : ' . $this->property->title,
            'time' => now()->diffForHumans(),
        ]);
    }
}