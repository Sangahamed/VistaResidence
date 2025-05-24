<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer des slugs uniques pour les propriétés';

    /**
     * Execute the console command.
     */
  public function handle()
{
    Property::all()->each(function ($property) {
        if (empty($property->slug)) {
            $property->slug = $property->generateUniqueSlug($property->title);
            $property->save();
        }
    });

    $this->info('Slugs generated successfully!');
}

}
