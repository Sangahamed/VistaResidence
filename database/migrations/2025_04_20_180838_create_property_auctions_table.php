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
        Schema::create('property_auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('starting_price', 12, 2);
            $table->decimal('reserve_price', 12, 2)->nullable();
            $table->decimal('current_bid', 12, 2)->nullable();
            $table->foreignId('current_bidder_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('total_bids')->default(0);
            // $table->timestamp('start_date');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();

            // $table->timestamp('end_date');
            $table->enum('status', ['upcoming', 'active', 'ended', 'cancelled'])->default('upcoming');
            $table->decimal('bid_increment', 12, 2)->default(1000.00);
            $table->boolean('auto_extend')->default(true);
            $table->integer('auto_extend_minutes')->default(10);
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_auctions');
    }
};
