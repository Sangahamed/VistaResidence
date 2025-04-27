<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des modules (extensions)
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('version');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_core')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // Table pivot entreprises-modules
        Schema::create('company_module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_module');
        Schema::dropIfExists('modules');
    }
};

