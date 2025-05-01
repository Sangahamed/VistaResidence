<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class MonthlyReportNotification extends Notification implements ShouldQueue
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
        $month = date('F Y', strtotime($startDate));
        
        $subject = match($this->reportType) {
            'agency' => 'Rapport mensuel de l\'agence ' . $this->reportData['agency']['name'],
            'company' => 'Rapport mensuel de l\'entreprise ' . $this->reportData['company']['name'],
            default => 'Rapport mensuel global',
        };
        
        $mail = (new MailMessage)
            ->subject($subject . ' - ' . $month)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Veuillez trouver ci-joint le rapport mensuel pour ' . $month . '.');
            
        // Ajouter des statistiques clés dans l'email
        $mail->line('Statistiques clés :')
            ->line('- Nouvelles propriétés : ' . $this->reportData['properties']['new'])
            ->line('- Propriétés publiées : ' . $this->reportData['properties']['published'])
            ->line('- Propriétés vendues : ' . $this->reportData['properties']['sold'])
            ->line('- Total des propriétés : ' . $this->reportData['properties']['total'])
            ->line('- Nouveaux leads : ' . $this->reportData['leads']['new'])
            ->line('- Total des leads : ' . $this->reportData['leads']['total'])
            ->line('- Visites programmées : ' . $this->reportData['visits']['scheduled'])
            ->line('- Visites effectuées : ' . $this->reportData['visits']['completed'])
            ->line('- Total des visites : ' . $this->reportData['visits']['total']);
            
        // Ajouter le rapport complet en pièce jointe
        if (Storage::exists($this->reportFile)) {
            $mail->attachData(
                Storage::get($this->reportFile),
                basename($this->reportFile),
                [
                    'mime' => 'application/pdf',
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
        $month = date('F Y', strtotime($this->reportData['period']['start']));
        
        return [
            'report_type' => $this->reportType,
            'period' => $this->reportData['period'],
            'file' => $this->reportFile,
            'type' => 'monthly_report',
            'message' => 'Rapport mensuel disponible - ' . $month,
        ];
    }
}