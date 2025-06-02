<?php

namespace App\Notifications;

use App\Models\Property;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected $matchCriteria;

    /**
     * Create a new notification instance.
     *
     * @param Property $property
     * @param array $matchCriteria
     * @return void
     */
    public function __construct(Property $property, array $matchCriteria = [])
    {
        $this->property = $property;
        $this->matchCriteria = $matchCriteria;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('properties.show', $this->property->id);
        
        return (new MailMessage)
            ->subject('Nouvelle propriété correspondant à votre recherche')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle propriété correspondant à vos critères de recherche a été ajoutée :')
            ->line('**' . $this->property->title . '**')
            ->line('Type: ' . ucfirst($this->property->type))
            ->line('Prix: ' . number_format($this->property->price, 0, ',', ' ') . ' FCFA')
            ->line('Localisation: ' . $this->property->city)
            ->action('Voir la propriété', route('properties.show', $this->property->id))
            ->line('Merci d\'utiliser notre plateforme !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'title' => $this->property->title,
            'address' => $this->property->address,
            'price' => $this->property->price,
            'match_criteria' => $this->matchCriteria,
            'image' => $this->property->featured_image,
            'message' => 'Une nouvelle propriété correspond à votre recherche',
            'url' => route('properties.show', $this->property->id)
        ];
    }
}
