<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class WeeklyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reportType;
    protected $reportData;
    protected $reportFile;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $reportType, array $reportData, string $reportFile)
    {
        $this->reportType = $reportType; // 'agency', 'company', ou 'global'
        $this->reportData = $reportData;
        $this->reportFile = $reportFile;
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
        $startDate = $this->reportData['period']['start'];
        $endDate = $this->reportData['period']['end'];
        
        $subject = match($this->reportType) {
            'agency' => 'Rapport hebdomadaire de l\'agence ' . $this->reportData['agency']['name'],
            'company' => 'Rapport hebdomadaire de l\'entreprise ' . $this->reportData['company']['name'],
            default => 'Rapport hebdomadaire global',
        };
        
        $mail = (new MailMessage)
            ->subject($subject . ' - ' . $startDate . ' au ' . $endDate)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Veuillez trouver ci-joint le rapport hebdomadaire pour la période du ' . $startDate . ' au ' . $endDate . '.');
            
        // Ajouter des statistiques clés dans l'email
        $mail->line('Statistiques clés :')
            ->line('- Nouvelles propriétés : ' . $this->reportData['properties']['new'])
            ->line('- Propriétés publiées : ' . $this->reportData['properties']['published'])
            ->line('- Propriétés vendues : ' . $this->reportData['properties']['sold'])
            ->line('- Nouveaux leads : ' . $this->reportData['leads']['new'])
            ->line('- Visites programmées : ' . $this->reportData['visits']['scheduled'])
            ->line('- Visites effectuées : ' . $this->reportData['visits']['completed']);
            
        // Ajouter le rapport complet en pièce jointe
        if (Storage::exists($this->reportFile)) {
            $mail->attachData(
                Storage::get($this->reportFile),
                basename($this->reportFile),
                [
                    'mime' => 'application/json',
                ]
            );
        }
        
        // Ajouter un lien vers le tableau de bord
        $dashboardUrl = match($this->reportType) {
            'agency' => url('/agencies/' . $this->reportData['agency']['id'] . '/dashboard'),
            'company' => url('/companies/' . $this->reportData['company']['id'] . '/dashboard'),
            default => url('/dashboard'),
        };
        
        return $mail->action('Voir le tableau de bord', $dashboardUrl)
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
            'report_type' => $this->reportType,
            'period' => $this->reportData['period'],
            'file' => $this->reportFile,
            'type' => 'weekly_report',
            'message' => 'Rapport hebdomadaire disponible - ' . $this->reportData['period']['start'] . ' au ' . $this->reportData['period']['end'],
        ];
    }
}