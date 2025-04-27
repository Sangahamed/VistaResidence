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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['rental', 'sale'])->default('rental');
            $table->enum('status', ['draft', 'pending', 'active', 'terminated', 'expired'])->default('draft');
            $table->decimal('amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'annually', 'one_time'])->default('monthly');
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
