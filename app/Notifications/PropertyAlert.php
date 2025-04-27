<?php

namespace App\Notifications;

use App\Models\Property;
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
            ->subject('New Property Matching Your Criteria')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We found a new property that matches your search criteria:')
            ->line($this->property->title)
            ->line($this->property->address)
            ->line('Price: $' . number_format($this->property->price, 2))
            ->action('View Property', $url)
            ->line('Thank you for using our application!');
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
            'url' => route('properties.show', $this->property->id)
        ];
    }
}
