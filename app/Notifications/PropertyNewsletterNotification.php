<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PropertyNewsletterNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $properties;
    protected $startDate;
    protected $endDate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $properties, Carbon $startDate, Carbon $endDate)
    {
        $this->properties = $properties;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Nouvelles propriétés de la semaine')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Voici les nouvelles propriétés publiées cette semaine :');
            
        // Regrouper les propriétés par type de transaction
        $propertiesByType = $this->properties->groupBy('transaction_type');
        
        // Ajouter les propriétés à vendre
        if ($propertiesByType->has('sale')) {
            $mail->line('');
            $mail->line('**Propriétés à vendre :**');
            
            foreach ($propertiesByType['sale'] as $property) {
                $mail->line('');
                $mail->line('- **' . $property->title . '**');
                $mail->line('  ' . $property->address . ', ' . $property->city);
                $mail->line('  ' . number_format($property->price, 0, ',', ' ') . ' €');
                $mail->line('  ' . $property->bedrooms . ' chambres, ' . $property->bathrooms . ' salles de bain, ' . $property->area . ' m²');
                $mail->line('  [Voir la propriété](' . url('/properties/' . $property->id) . ')');
            }
        }
        
        // Ajouter les propriétés à louer
        if ($propertiesByType->has('rent')) {
            $mail->line('');
            $mail->line('**Propriétés à louer :**');
            
            foreach ($propertiesByType['rent'] as $property) {
                $mail->line('');
                $mail->line('- **' . $property->title . '**');
                $mail->line('  ' . $property->address . ', ' . $property->city);
                $mail->line('  ' . number_format($property->price, 0, ',', ' ') . ' € / mois');
                $mail->line('  ' . $property->bedrooms . ' chambres, ' . $property->bathrooms . ' salles de bain, ' . $property->area . ' m²');
                $mail->line('  [Voir la propriété](' . url('/properties/' . $property->id) . ')');
            }
        }
        
        return $mail->action('Voir toutes les propriétés', url('/properties'))
            ->line('')
            ->line('Vous recevez cet email car vous êtes abonné à notre newsletter de propriétés.')
            ->line('Pour modifier vos préférences de notification, cliquez sur le lien ci-dessous :')
            ->action('Gérer mes préférences', url('/notifications/preferences'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'properties_count' => $this->properties->count(),
            'period' => [
                'start' => $this->startDate->toIso8601String(),
                'end' => $this->endDate->toIso8601String(),
            ],
            'type' => 'property_newsletter',
            'message' => 'Newsletter des nouvelles propriétés de la semaine',
        ];
    }
}