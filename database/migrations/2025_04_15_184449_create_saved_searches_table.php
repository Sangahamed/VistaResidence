<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->index(['session_id']);
            $table->string('name');
            $table->json('criteria');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saved_searches');
    }
};