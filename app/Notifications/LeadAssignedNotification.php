<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lead;
    protected $agent;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lead $lead, Agent $agent, User $assignedBy)
    {
        $this->lead = $lead;
        $this->agent = $agent;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Récupérer les préférences de notification de l'utilisateur
        $channels = ['database', 'broadcast'];
        
        if ($notifiable->notificationPreferences && $notifiable->notificationPreferences->email_lead_assigned) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/leads/' . $this->lead->id);
        
        return (new MailMessage)
            ->subject('Lead assigné : ' . $this->lead->name)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un lead vous a été assigné par ' . $this->assignedBy->name . '.')
            ->line('Nom du lead : ' . $this->lead->name)
            ->line('Email : ' . $this->lead->email)
            ->line('Téléphone : ' . $this->lead->phone)
            ->line('Source : ' . $this->lead->source)
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
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'assigned_by' => [
                'id' => $this->assignedBy->id,
                'name' => $this->assignedBy->name,
            ],
            'type' => 'lead_assigned',
            'message' => 'Lead assigné : ' . $this->lead->name,
        
            'lead_assigned',
            'message' => 'Lead assigné : ' . $this->lead->name,
        ];
    }
    
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'lead_id' => $this->lead->id,
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'assigned_by' => [
                'id' => $this->assignedBy->id,
                'name' => $this->assignedBy->name,
            ],
            'type' => 'lead_assigned',
            'message' => 'Lead assigné : ' . $this->lead->name,
            'time' => now()->diffForHumans(),
        ]);
    }
}