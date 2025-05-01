<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-temp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les fichiers temporaires et obsolètes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage des fichiers temporaires...');
        
        // Nettoyer les fichiers temporaires
        $this->cleanupDirectory('temp', 1); // 1 jour
        
        // Nettoyer les exports obsolètes
        $this->cleanupDirectory('exports', 7); // 7 jours
        
        // Nettoyer les anciens logs
        $this->cleanupDirectory('logs', 30); // 30 jours
        
        // Nettoyer les images de propriétés supprimées
        $this->cleanupOrphanedPropertyImages();
        
        $this->info('Nettoyage des fichiers temporaires terminé.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Nettoie les fichiers d'un répertoire plus anciens qu'un certain nombre de jours.
     */
    private function cleanupDirectory($directory, $days)
    {
        $this->info("Nettoyage du répertoire {$directory} (fichiers de plus de {$days} jours)...");
        
        $cutoffDate = Carbon::now()->subDays($days);
        $files = Storage::files($directory);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));
            
            if ($lastModified->lt($cutoffDate)) {
                Storage::delete($file);
                $deletedCount++;
                $this->info("Fichier supprimé : {$file}");
            }
        }
        
        $this->info("{$deletedCount} fichiers supprimés du répertoire {$directory}.");
    }
    
    /**
     * Nettoie les images de propriétés qui n'existent plus dans la base de données.
     */
    private function cleanupOrphanedPropertyImages()
    {
        $this->info("Nettoyage des images de propriétés orphelines...");
        
        $propertyIds = \App\Models\Property::pluck('id')->toArray();
        $directories = Storage::directories('properties');
        $deletedCount = 0;
        
        foreach ($directories as $directory) {
            $dirId = basename($directory);
            
            if (is_numeric($dirId) && !in_array($dirId, $propertyIds)) {
                $files = Storage::files($directory);
                
                foreach ($files as $file) {
                    Storage::delete($file);
                    $deletedCount++;
                }
                
                Storage::deleteDirectory($directory);
                $this->info("Répertoire supprimé : {$directory} ({$deletedCount} fichiers)");
            }
        }
        
        $this->info("{$deletedCount} images de propriétés orphelines supprimées.");
    }
}