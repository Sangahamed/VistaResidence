<?php

namespace App\Notifications;

use App\Models\PropertyVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class VisitReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $visit;
    public $recipientType; // 'client' ou 'agent'
    public $reminderType;  // '24h' ou '1h'

    public function __construct(PropertyVisit $visit, string $recipientType, string $reminderType)
    {
        $this->visit = $visit;
        $this->recipientType = $recipientType;
        $this->reminderType = $reminderType;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $visitDate = $this->visit->visit_date;
        $startTime = Carbon::parse($this->visit->visit_time_start)->format('H:i');
        $endTime = Carbon::parse($this->visit->visit_time_end)->format('H:i');
        
        $subject = $this->reminderType === '24h' 
            ? 'Rappel : Visite prévue demain'
            : 'Rappel : Visite prévue dans 1 heure';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . $notifiable->name . ',');

        if ($this->recipientType === 'client') {
            $mail->line($this->reminderType === '24h' 
                ? 'Nous vous rappelons votre visite prévue demain :'
                : 'Nous vous rappelons votre visite prévue dans 1 heure :')
                ->line('Propriété : ' . ($this->visit->property->title ?? $this->visit->title))
                ->line('Date : ' . $visitDate->format('d/m/Y'))
                ->line('Heure : ' . $startTime . ' - ' . $endTime);

            if ($this->visit->agent) {
                $mail->line('Agent : ' . $this->visit->agent->name);
            }
        } else {
            $mail->line($this->reminderType === '24h' 
                ? 'Vous avez une visite prévue demain :'
                : 'Vous avez une visite prévue dans 1 heure :')
                ->line('Propriété : ' . ($this->visit->property->title ?? $this->visit->title))
                ->line('Date : ' . $visitDate->format('d/m/Y'))
                ->line('Heure : ' . $startTime . ' - ' . $endTime)
                ->line('Visiteur : ' . $this->visit->visitor->name)
                ->line('Téléphone : ' . ($this->visit->visitor->phone ?? 'Non renseigné'));
        }

        return $mail->action('Voir les détails', url('/visits/' . $this->visit->id))
                   ->line('Merci d\'utiliser notre application!');
    }

    public function toArray($notifiable)
    {
        return [
            'visit_id' => $this->visit->id,
            'property_id' => $this->visit->property_id,
            'property_title' => $this->visit->property->title ?? $this->visit->title,
            'visit_date' => $this->visit->visit_date->toDateString(),
            'visit_time' => $this->visit->visit_time_start . ' - ' . $this->visit->visit_time_end,
            'type' => 'visit_reminder',
            'recipient_type' => $this->recipientType,
            'reminder_type' => $this->reminderType,
            'title' => 'Nous vous rappelons : ' . $this->visit->property->title,
            'message' => 'La visite est prévue ' . $this->reminderType === '24h' 
                ? 'Rappel de visite pour demain' 
                : 'Rappel de visite dans 1 heure',
        ];
    }
}