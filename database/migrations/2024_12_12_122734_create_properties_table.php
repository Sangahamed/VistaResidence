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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('categories');
            $table->text('description')->nullable();
            $table->json('photo');
            $table->string('city');
            $table->string('district');
            $table->string('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('rooms');
            $table->string('bathrooms');
            $table->string('bedrooms');
            $table->string('floors');
            $table->string('area');
            $table->string('price');
            $table->string('period');
            $table->json('features')->nullable();
            $table->string('usage');
            $table->string('status')->default('available');
            $table->foreignId('owner_id')->constrained('users');
            $table->timestamps();
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
