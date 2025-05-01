<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée une sauvegarde de la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sauvegarde de la base de données...');
        
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "database_backup_{$date}.sql";
        $backupPath = storage_path("app/backups/{$filename}");
        
        // Créer le répertoire de sauvegarde s'il n'existe pas
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }
        
        // Récupérer les informations de connexion à la base de données
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        
        // Construire la commande mysqldump
        $command = [
            'mysqldump',
            "--host={$host}",
            "--port={$port}",
            "--user={$username}",
            "--password={$password}",
            $database,
            '--single-transaction',
            '--skip-lock-tables',
        ];
        
        try {
            $process = new Process($command);
            $process->setTimeout(3600); // 1 heure
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            $output = $process->getOutput();
            Storage::put("backups/{$filename}", $output);
            
            // Compresser le fichier
            $compressedFilename = "{$filename}.gz";
            $compressCommand = "gzip " . storage_path("app/backups/{$filename}");
            exec($compressCommand);
            
            $this->info("Sauvegarde créée : backups/{$compressedFilename}");
            
            // Supprimer les anciennes sauvegardes (garder les 7 dernières)
            $this->cleanupOldBackups();
            
            // Optionnel : Envoyer la sauvegarde vers un stockage externe (S3, etc.)
            $this->uploadToExternalStorage("backups/{$compressedFilename}");
            
            $this->info('Sauvegarde de la base de données terminée.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Erreur lors de la sauvegarde de la base de données : " . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Supprime les anciennes sauvegardes (garde les 7 dernières).
     */
    private function cleanupOldBackups()
    {
        $this->info('Nettoyage des anciennes sauvegardes...');
        
        $backups = collect(Storage::files('backups'))
            ->filter(function ($file) {
                return preg_match('/database_backup_.*\.sql\.gz$/', $file);
            })
            ->sortByDesc(function ($file) {
                return Storage::lastModified($file);
            });
            
        if ($backups->count() > 7) {
            $backupsToDelete = $backups->slice(7);
            
            foreach ($backupsToDelete as $backup) {
                Storage::delete($backup);
                $this->info("Ancienne sauvegarde supprimée : {$backup}");
            }
        }
    }
    
    /**
     * Envoie la sauvegarde vers un stockage externe.
     */
    private function uploadToExternalStorage($file)
    {
        // Cette méthode peut être personnalisée pour envoyer la sauvegarde vers un stockage externe
        // comme Amazon S3, Google Cloud Storage, etc.
        
        // Exemple avec S3 (nécessite la configuration de S3 dans config/filesystems.php)
        if (config('filesystems.disks.s3')) {
            $this->info('Envoi de la sauvegarde vers S3...');
            
            try {
                $s3Path = 'database-backups/' . basename($file);
                Storage::disk('s3')->put($s3Path, Storage::get($file));
                $this->info("Sauvegarde envoyée vers S3 : {$s3Path}");
            } catch (\Exception $e) {
                $this->error("Erreur lors de l'envoi vers S3 : " . $e->getMessage());
            }
        }
    }
}