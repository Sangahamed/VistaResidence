<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'invitations.accept',
            $this->invitation->expires_at,
            ['token' => $this->invitation->token]
        );

        $inviter = $this->invitation->inviter;
        $role = $this->invitation->role;
        $company = $this->invitation->company;

        $mailMessage = (new MailMessage)
            ->subject('Invitation à rejoindre ImmoConnect')
            ->greeting('Bonjour !')
            ->line($inviter->name . ' vous invite à rejoindre ImmoConnect en tant que ' . $role->name . '.')
            ->line('Cette invitation expirera le ' . $this->invitation->expires_at->format('d/m/Y à H:i') . '.');

        if ($company) {
            $mailMessage->line('Vous rejoindrez l\'entreprise ' . $company->name . '.');
        }

        return $mailMessage
            ->action('Accepter l\'invitation', $url)
            ->line('Si vous n\'attendiez pas cette invitation, vous pouvez ignorer cet email.');
    }
}