<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivityReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;
    public $type;

    public function __construct(array $report, string $type)
    {
        $this->report = $report;
        $this->type = $type;
    }

    public function build()
    {
        $subject = match($this->type) {
            'daily' => 'Rapport d\'activité journalier - ' . $this->report['period']['end'],
            'monthly' => 'Rapport d\'activité mensuel - ' . date('F Y', strtotime($this->report['period']['start'])),
            'quarterly' => 'Rapport d\'activité trimestriel - Q' . ceil(date('n', strtotime($this->report['period']['start'])) / 3) . ' ' . date('Y', strtotime($this->report['period']['start'])),
            default => 'Rapport d\'activité - ' . $this->report['period']['start'] . ' à ' . $this->report['period']['end'],
        };

        return $this->subject($subject)
                    ->view('email-templates.activity-report')
                    ->attachFromStorage('reports/' . basename($this->report['pdf']));
    }
}
