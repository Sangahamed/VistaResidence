<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = route('messenger', ['user_id' => $this->sender->id]);

        return (new MailMessage)
            ->subject('Nouveau message de ' . $this->sender->name)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Vous avez reçu un nouveau message de ' . $this->sender->name . '.')
            ->line('Message : ' . (strlen($this->message) > 100 ? substr($this->message, 0, 100) . '...' : $this->message))
            ->action('Répondre au message', $url)
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message' => $this->message,
        ];
    }
}