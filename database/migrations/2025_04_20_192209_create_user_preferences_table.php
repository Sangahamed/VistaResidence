<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('preferred_locations')->nullable();
            $table->json('preferred_property_types')->nullable();
            $table->decimal('min_price', 12, 2)->nullable();
            $table->decimal('max_price', 12, 2)->nullable();
            $table->integer('min_bedrooms')->nullable();
            $table->integer('min_bathrooms')->nullable();
            $table->integer('min_surface')->nullable();
            $table->boolean('has_garden')->nullable();
            $table->boolean('has_balcony')->nullable();
            $table->boolean('has_parking')->nullable();
            $table->boolean('has_elevator')->nullable();
            $table->json('preferred_amenities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
