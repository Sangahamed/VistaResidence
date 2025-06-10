<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Company;

class CompanyRejected extends Notification implements ShouldQueue
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
            ->subject('Votre entreprise a été rejetée')
            ->line('Nous sommes désolés, mais votre entreprise "' . $this->company->name . '" a été rejetée.')
            ->line('Si vous souhaitez en savoir plus, veuillez contacter l’administration.')
            ->action('Retourner à l’accueil', route('home'))
            ->line('Merci de votre compréhension.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'Entreprise Rejetée',
            'title' => 'Rejet de l’entreprise : ' . $this->company->name,
            'message' => 'Votre entreprise a été rejetée. Veuillez contacter l’administration pour plus de détails.',
            'url' => route('home'),
        ];
    }
}
