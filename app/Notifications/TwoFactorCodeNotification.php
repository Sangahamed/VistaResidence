<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;
    protected $type;

    public function __construct($code, $type = 'email')
    {
        $this->code = $code;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = match($this->type) {
            'sms_backup' => '🔐 Code de vérification (Copie email)',
            'email_fallback' => '🔐 Code de vérification (SMS indisponible)',
            default => '🔐 Votre code de vérification VistaImmob'
        };

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Votre code de vérification à deux facteurs est :')
            ->line("**{$this->code}**")
            ->line('Ce code expire dans 10 minutes.')
            ->line('Si vous n\'avez pas demandé ce code, veuillez ignorer cet email.');

        if ($this->type === 'sms_backup') {
            $message->line('📱 Ce code a également été envoyé par SMS à votre numéro de téléphone.');
        } elseif ($this->type === 'email_fallback') {
            $message->line('⚠️ L\'envoi par SMS n\'est pas disponible, nous vous envoyons donc le code par email.');
        }

        return $message
            ->action('Se connecter', url('/login'))
            ->line('Pour votre sécurité, ne partagez jamais ce code avec personne.');
    }
}
