<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->string('action', 50)->index();
            $table->text('details')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->string('suspicion_type', 50)->nullable()->index();
            $table->string('risk_score', 10)->nullable()->index();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
            
            $table->index(['created_at', 'is_suspicious']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
