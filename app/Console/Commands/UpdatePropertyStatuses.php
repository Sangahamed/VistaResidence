<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Events\PropertyStatusChanged;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UpdatePropertyStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-property-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour le statut des propriétés en fonction de leur date de publication et d\'expiration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des statuts de propriétés...');
        
        $now = Carbon::now();
        $systemUser = \App\Models\User::where('email', 'system@vistaimmob.com')->first();
        
        if (!$systemUser) {
            $this->error('Utilisateur système non trouvé. Création d\'un utilisateur système...');
            $systemUser = \App\Models\User::create([
                'name' => 'Système',
                'email' => 'system@vistaimmob.com',
                'password' => bcrypt(Str::random(32)),
                'role' => 'system',
            ]);
        }
        
        // 1. Publier les propriétés dont la date de publication est aujourd'hui
        $propertiesToPublish = Property::where('status', 'pending')
            ->whereDate('published_at', '<=', $now->toDateString())
            ->get();
            
        $this->info("Nombre de propriétés à publier : " . $propertiesToPublish->count());
        
        foreach ($propertiesToPublish as $property) {
            $oldStatus = $property->status;
            $property->status = 'published';
            $property->save();
            
            event(new PropertyStatusChanged($property, $systemUser, $oldStatus, 'published'));
            $this->info("Propriété publiée: {$property->title} (ID: {$property->id})");
        }
        
        // 2. Marquer comme expirées les propriétés publiées depuis plus de X jours (selon la configuration)
        $expirationDays = config('property.expiration_days', 90);
        $expirationDate = $now->subDays($expirationDays)->toDateString();
        
        $expiredProperties = Property::where('status', 'published')
            ->whereDate('published_at', '<=', $expirationDate)
            ->whereNull('expires_at')
            ->orWhere(function ($query) use ($now) {
                $query->whereDate('expires_at', '<=', $now->toDateString());
            })
            ->get();
            
        $this->info("Nombre de propriétés expirées : " . $expiredProperties->count());
        
        foreach ($expiredProperties as $property) {
            $oldStatus = $property->status;
            $property->status = 'expired';
            $property->save();
            
            event(new PropertyStatusChanged($property, $systemUser, $oldStatus, 'expired'));
            $this->info("Propriété expirée: {$property->title} (ID: {$property->id})");
        }
        
        $this->info('Mise à jour des statuts de propriétés terminée.');
        
        return Command::SUCCESS;
    }
}