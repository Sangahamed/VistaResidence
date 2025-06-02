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
        Schema::create('property_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('view_count')->default(1);
            $table->integer('interaction_score')->default(0);
            $table->timestamp('last_viewed_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'session_id']);
            $table->index('last_viewed_at');
            $table->index('interaction_score');
            $table->spatialIndex(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_views');
    }
};
