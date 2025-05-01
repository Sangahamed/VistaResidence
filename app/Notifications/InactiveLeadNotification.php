<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InactiveLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lead;
    protected $level;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lead $lead, string $level)
    {
        $this->lead = $lead;
        $this->level = $level; // 'warning' ou 'critical'
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/leads/' . $this->lead->id);
        $warningThreshold = config('lead.warning_threshold', 7);
        $criticalThreshold = config('lead.critical_threshold', 14);
        
        $mail = (new MailMessage)
            ->subject($this->level === 'warning' 
                ? 'Alerte : Lead inactif depuis ' . $warningThreshold . ' jours' 
                : 'Alerte critique : Lead inactif depuis ' . $criticalThreshold . ' jours')
            ->greeting('Bonjour ' . $notifiable->name . ',');
            
        if ($this->level === 'warning') {
            $mail->line('Un lead n\'a pas eu d\'activité depuis ' . $warningThreshold . ' jours.')
                ->line('Il est recommandé de contacter ce lead pour maintenir la relation.');
        } else {
            $mail->line('Un lead n\'a pas eu d\'activité depuis ' . $criticalThreshold . ' jours.')
                ->line('Il est urgent de contacter ce lead pour éviter de perdre l\'opportunité.');
        }
        
        return $mail->line('Lead : ' . $this->lead->name)
            ->line('Email : ' . $this->lead->email)
            ->line('Téléphone : ' . $this->lead->phone)
            ->line('Source : ' . $this->lead->source)
            ->line('Statut : ' . $this->lead->status)
            ->action('Voir le lead', $url)
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
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'lead_email' => $this->lead->email,
            'lead_phone' => $this->lead->phone,
            'level' => $this->level,
            'type' => 'inactive_lead',
            'message' => $this->level === 'warning' 
                ? 'Alerte : Lead inactif - ' . $this->lead->name 
                : 'Alerte critique : Lead inactif - ' . $this->lead->name,
        ];
    }
}