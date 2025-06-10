<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Company;

class CompanyApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $company;

    /**
     * Create a new notification instance.
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre entreprise a été approuvée')
            ->line('Félicitations ! Votre entreprise "' . $this->company->name . '" a été approuvée.')
            ->action('Accéder au tableau de bord', route('dashboard'))
            ->line('Merci de votre confiance.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'Entreprise Approuvée',
            'title' => 'Confirmation de l’entreprise : ' . $this->company->name,
            'message' => 'Votre entreprise est désormais approuvée ! Vous pouvez accéder à votre tableau de bord.',
            'url' => route('dashboard'),
        ];
    }
}
