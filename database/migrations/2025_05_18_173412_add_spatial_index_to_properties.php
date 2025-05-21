<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Vérifier si la colonne 'location' existe déjà avant de l'ajouter
        if (!Schema::hasColumn('properties', 'location')) {
            DB::statement('ALTER TABLE properties ADD COLUMN location POINT NOT NULL AFTER longitude');
        }

        // Mise à jour des données existantes (conversion des latitudes/longitudes en POINT)
        DB::statement('
            UPDATE properties 
            SET location = ST_PointFromText(
                CONCAT("POINT(", COALESCE(longitude, 0), " ", COALESCE(latitude, 0), ")"),
                4326
            )
            WHERE latitude IS NOT NULL AND longitude IS NOT NULL
        ');

        // Créer l'index spatial sur la colonne 'location'
        DB::statement('ALTER TABLE properties ADD SPATIAL INDEX properties_location_index (location)');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Supprimer l'index spatial et la colonne 'location'
        DB::statement('ALTER TABLE properties DROP INDEX properties_location_index');
        DB::statement('ALTER TABLE properties DROP COLUMN location');
    }
};
