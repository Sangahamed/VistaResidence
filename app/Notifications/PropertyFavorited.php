<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyFavorited extends Notification
{
    use Queueable;

    public $property;
    public $user;

    public function __construct($property, $user)
    {
        $this->property = $property;
        $this->user = $user;
    }

     public function via($notifiable)
    {
        return $notifiable->notificationPreference->getNotificationChannels();
    }

   public function toDatabase($notifiable)
{
    return [
        'type' => 'property_favorited',
        'message' => $this->user->name.' a ajouté votre propriété à ses favoris',
        'property_id' => $this->property->id,
        'property_title' => $this->property->title,
        'user_id' => $this->user->id,
        'user_name' => $this->user->name,
        'action_url' => route('properties.show', $this->property),
    ];
}
}