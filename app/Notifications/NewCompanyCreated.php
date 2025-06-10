<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Company;


class NewCompanyCreated extends Notification
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
            ->subject('Nouvelle entreprise en attente')
            ->line('Une nouvelle entreprise a été créée: ' . $this->company->name)
            ->action('Vérifier', route('admin.companies.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'Entreprise en attente',
            'title' => 'Entreprise en attente de  Confirmation : ' . $this->company->name,
            'message' => 'Nouvelle entreprise: ' . $this->company->name,
            'url' => route('admin.companies.index')
        ];
    }
}
