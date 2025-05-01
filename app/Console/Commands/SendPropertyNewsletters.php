<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Property;
use App\Notifications\PropertyNewsletterNotification;
use Carbon\Carbon;

class SendPropertyNewsletters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-property-newsletters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des newsletters hebdomadaires avec les nouvelles propriétés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Envoi des newsletters de propriétés...');
        
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now();
        
        // Récupérer les nouvelles propriétés publiées cette semaine
        $newProperties = Property::where('status', 'published')
            ->whereBetween('published_at', [$startDate, $endDate])
            ->with(['propertyType', 'propertyFeatures', 'agency', 'agent.user'])
            ->get();
            
        $this->info("Nombre de nouvelles propriétés : " . $newProperties->count());
        
        if ($newProperties->count() === 0) {
            $this->info('Aucune nouvelle propriété à inclure dans la newsletter.');
            return Command::SUCCESS;
        }
        
        // Regrouper les propriétés par type (vente, location)
        $propertiesByType = $newProperties->groupBy('transaction_type');
        
        // Récupérer les utilisateurs qui ont souscrit à la newsletter
        $subscribers = User::whereHas('notificationPreferences', function ($query) {
                $query->where('email_property_newsletter', true);
            })
            ->get();
            
        $this->info("Nombre d'abonnés à la newsletter : " . $subscribers->count());
        
        foreach ($subscribers as $subscriber) {
            // Filtrer les propriétés en fonction des préférences de l'utilisateur
            // (à implémenter selon les préférences spécifiques de votre application)
            $filteredProperties = $this->filterPropertiesByUserPreferences($newProperties, $subscriber);
            
            if ($filteredProperties->count() > 0) {
                $subscriber->notify(new PropertyNewsletterNotification($filteredProperties, $startDate, $endDate));
                $this->info("Newsletter envoyée à {$subscriber->name} ({$subscriber->email}) avec {$filteredProperties->count()} propriétés");
            } else {
                $this->info("Aucune propriété correspondant aux préférences de {$subscriber->name} ({$subscriber->email})");
            }
        }
        
        $this->info('Envoi des newsletters de propriétés terminé.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Filtre les propriétés en fonction des préférences de l'utilisateur.
     */
    private function filterPropertiesByUserPreferences($properties, $user)
    {
        // Cette méthode peut être personnalisée en fonction des préférences spécifiques de votre application
        // Par exemple, filtrer par type de propriété, localisation, budget, etc.
        
        // Pour cet exemple, nous retournons simplement toutes les propriétés
        // Dans une implémentation réelle, vous devriez vérifier les préférences de l'utilisateur
        return $properties;
    }
}