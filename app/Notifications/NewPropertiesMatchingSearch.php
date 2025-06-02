<?php

namespace App\Notifications;

use App\Models\SavedSearch;
use App\Models\User;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPropertiesMatchingSearch extends Notification implements ShouldQueue
{
    use Queueable;

    protected $savedSearch;
    protected $properties;

    /**
     * Create a new notification instance.
     */
    public function __construct(SavedSearch $savedSearch, $properties)
    {
        $this->savedSearch = $savedSearch;
        $this->properties = $properties;
    }

    /**
     * Get the notification's delivery channels.
     */
   public function via($notifiable)
    {
        return $notifiable->notificationPreference->getNotificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Nouvelles propriétés correspondant à votre recherche')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Nous avons trouvé ' . count($this->properties) . ' nouvelle(s) propriété(s) correspondant à votre recherche "' . $this->savedSearch->name . '".');

        // Ajouter les propriétés trouvées
        foreach ($this->properties as $property) {
            $mailMessage->line('- ' . $property->title . ' - ' . number_format($property->price) . ' CFA - ' . $property->city);
        }

        return $mailMessage
            ->action('Voir les résultats', route('properties.search.load', $this->savedSearch))
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'search_params' => $this->searchParams,
            'title' => "propriété correspond à votre recherche",
            'message' => 'Une nouvelle propriété correspond à votre recherche',
            'url' => route('properties.show', $this->property->id),
        ];
    }
}