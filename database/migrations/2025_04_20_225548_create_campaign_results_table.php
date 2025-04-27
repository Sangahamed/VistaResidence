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
        Schema::create('campaign_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_campaign_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('leads_generated')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_results');
    }
};
