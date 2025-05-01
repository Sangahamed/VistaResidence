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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Préférences pour les emails
            $table->boolean('email_new_property')->default(true);
            $table->boolean('email_property_status')->default(true);
            $table->boolean('email_new_lead')->default(true);
            $table->boolean('email_lead_assigned')->default(true);
            $table->boolean('email_visit_requested')->default(true);
            $table->boolean('email_visit_status')->default(true);
            
            // Préférences pour les notifications push
            $table->boolean('push_new_property')->default(true);
            $table->boolean('push_property_status')->default(true);
            $table->boolean('push_new_lead')->default(true);
            $table->boolean('push_lead_assigned')->default(true);
            $table->boolean('push_visit_requested')->default(true);
            $table->boolean('push_visit_status')->default(true);
            
            // Préférences pour les SMS
            $table->boolean('sms_new_property')->default(false);
            $table->boolean('sms_property_status')->default(false);
            $table->boolean('sms_new_lead')->default(false);
            $table->boolean('sms_lead_assigned')->default(false);
            $table->boolean('sms_visit_requested')->default(false);
            $table->boolean('sms_visit_status')->default(false);
            
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