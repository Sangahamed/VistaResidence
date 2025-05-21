<?php

namespace App\Notifications\Property;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Created extends Notification implements ShouldQueue
{
    use Queueable;

    public $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function via($notifiable)
    {
        $channels = ['database'];
        
        if ($notifiable->notificationPreference->email_enabled) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Nouvelle propriété créée: {$this->property->title}")
            ->line("Vous avez créé une nouvelle propriété:")
            ->line("Titre: {$this->property->title}")
            ->action('Voir la propriété', route('properties.show', $this->property));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'property.created',
            'property_id' => $this->property->id,
            'title' => "Nouvelle propriété créée",
            'message' => "Vous avez créé: {$this->property->title}",
            'url' => route('properties.show', $this->property)
        ];
    }
}