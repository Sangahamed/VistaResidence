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
        Schema::create('notification_preferences', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
    
    // Paramètres globaux
    $table->boolean('email_enabled')->default(true);
    $table->boolean('push_enabled')->default(true);
    $table->boolean('sms_enabled')->default(false);
    $table->enum('frequency', ['instant', 'daily', 'weekly'])->default('instant');
    
    // Préférences par type de notification
    $table->json('preferences')->nullable()->comment('JSON contenant les préférences granulaires');
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
