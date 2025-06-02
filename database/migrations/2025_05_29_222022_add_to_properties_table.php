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
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('availability_status',['active','bloquer','en cours'])->default('en cours')->after('status');
            $table->float('viral_score')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property', function (Blueprint $table) {
            //
        });
    }
};
