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
        Schema::table('property_views', function (Blueprint $table) {
            $table->integer('interaction_score')->default(0)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            //
        });
    }
};
