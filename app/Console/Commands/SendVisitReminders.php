<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PropertyVisit;
use App\Notifications\VisitReminderNotification;
use Carbon\Carbon;

class SendVisitReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-visit-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels pour les visites à venir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Envoi des rappels de visites...');
        
        // Récupérer les visites prévues pour demain
        $tomorrow = Carbon::tomorrow();
        $visits = PropertyVisit::where('status', 'confirmed')
            ->whereDate('scheduled_at', $tomorrow->toDateString())
            ->with(['property', 'user', 'agent.user'])
            ->get();
            
        $this->info("Nombre de visites prévues pour demain : " . $visits->count());
        
        foreach ($visits as $visit) {
            // Envoyer un rappel au client
            if ($visit->user) {
                $visit->user->notify(new VisitReminderNotification($visit, 'client'));
                $this->info("Rappel envoyé au client: {$visit->user->name} pour la propriété: {$visit->property->title}");
            }
            
            // Envoyer un rappel à l'agent
            if ($visit->agent && $visit->agent->user) {
                $visit->agent->user->notify(new VisitReminderNotification($visit, 'agent'));
                $this->info("Rappel envoyé à l'agent: {$visit->agent->user->name} pour la propriété: {$visit->property->title}");
            }
        }
        
        $this->info('Envoi des rappels de visites terminé.');
        
        return Command::SUCCESS;
    }
}