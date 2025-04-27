<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        

        // Table des équipes
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('leader_id')->nullable()->constrained('users');
            $table->timestamps();
        });


        // Table pivot utilisateurs-équipes
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable();
            $table->timestamps();
        });

        // Table des invitations
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token')->unique();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('team_id')->nullable()->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('invited_by')->constrained('users');
            $table->string('status')->default('pending'); // pending, accepted, declined
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};

