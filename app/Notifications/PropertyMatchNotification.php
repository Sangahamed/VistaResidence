<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyMatchNotification extends Notification
{
    use Queueable;

    public $property;
    public $searchCriteria;

    public function __construct(Property $property, array $searchCriteria)
    {
        $this->property = $property;
        $this->searchCriteria = $searchCriteria;
    }

    public function via($notifiable)
    {
        return $notifiable->notificationPreference->getNotificationChannels();
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle propriété correspondant à votre recherche')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons trouvé une propriété qui correspond à vos critères :')
            ->line('**' . $this->property->title . '**')
            ->line('Type: ' . ucfirst($this->property->type))
            ->line('Prix: ' . number_format($this->property->price, 0, ',', ' ') . ' FCFA')
            ->line('Localisation: ' . $this->property->city)
            ->action('Voir la propriété', route('properties.show', $this->property->id))
            ->line('Merci d\'utiliser notre plateforme !');
    }

    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
             'title' => 'propriété correspondant à votre recherche',
            'message' => 'Nouvelle propriété correspondant à votre recherche',
            'url' => route('properties.show', $this->property->id)
        ];
    }
}
