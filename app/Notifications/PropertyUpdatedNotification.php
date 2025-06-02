<?php

namespace App\Notifications;


use App\Models\User;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PropertyUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected $changes;

    public function __construct($property, $changes)
    {
        $this->property = $property;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    

public function toMail($notifiable)
{
    Log::info('Notification email preparing for property: ' . $this->property->id);

    $mail = (new MailMessage)
        ->subject('Propriété mise à jour')
        ->line('La propriété a été mise à jour: ' . $this->property->title);

    foreach ($this->changes as $field => $value) {
        $mail->line(ucfirst($field) . ': ' . $value);
    }

    return $mail->action('Voir la propriété', url('/properties/' . $this->property->id))
                ->line('Merci d\'utiliser notre application!');
}


    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'title' => 'Propriété mise à jour',
            'message' => 'La propriété ' . $this->property->title . ' a été modifiée',
            'changes' => $this->changes,
            'url' => '/properties/' . $this->property->id,
        ];
    }
}