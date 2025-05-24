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
    public function up(): void
    {
        // Création de la table 'properties'
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // apartment, house, land, etc.
            $table->string('status'); // for_sale, for_rent, sold, rented
            $table->decimal('price', 12, 2);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->point('location')->nullable()->after('longitude'); // Ajout de la colonne 'location'
            $table->boolean('show_exact_location')->default(true);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->integer('year_built')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->boolean('has_virtual_tour')->default(false);
            $table->string('virtual_tour_type')->nullable(); // 'basic', 'panoramic', '3d'
            $table->string('virtual_tour_url')->nullable();
            $table->json('panoramic_images')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });

        // Conversion des coordonnées existantes et mise à jour de la colonne 'location'
        DB::statement('
            UPDATE properties 
            SET location = ST_PointFromText(
                CONCAT("POINT(", COALESCE(longitude, 0), " ", COALESCE(latitude, 0), ")"),
                4326
            )
            WHERE latitude IS NOT NULL AND longitude IS NOT NULL
        ');

        // Création de l'index spatial sur la colonne 'location'
        Schema::table('properties', function (Blueprint $table) {
            $table->spatialIndex('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
