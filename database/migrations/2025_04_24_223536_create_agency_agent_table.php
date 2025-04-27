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
        Schema::create('agency_agent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('agent');
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Un agent ne peut être associé qu'une seule fois à une agence
            $table->unique(['agency_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_agent');
    }
};
