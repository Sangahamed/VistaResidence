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
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['email', 'social_media', 'sms', 'print', 'web', 'other']);
            $table->enum('status', ['draft', 'scheduled', 'active', 'paused', 'completed']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('target_audience_size')->nullable();
            $table->text('target_criteria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_campaigns');
    }
};
