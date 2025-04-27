<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des projets
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('planning'); // planning, active, on_hold, completed, cancelled
            $table->integer('progress')->default(0);
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->timestamps();
        });

        // Table pivot projets-équipes
        Schema::create('project_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table pivot projets-utilisateurs
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable();
            $table->timestamps();
        });

        // Table des tâches
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('team_id')->nullable()->constrained();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->string('status')->default('to_do'); // to_do, in_progress, review, completed
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->integer('order')->default(0);
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->timestamps();
        });

        // Table des sous-tâches
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        // Table des commentaires de tâches
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table des pièces jointes de tâches
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('subtasks');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_user');
        Schema::dropIfExists('project_team');
        Schema::dropIfExists('projects');
    }
};
