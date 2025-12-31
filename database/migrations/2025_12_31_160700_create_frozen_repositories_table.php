<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('frozen_repositories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('github_id');
            $table->string('name');
            $table->string('full_name');
            $table->longText('description');
            $table->string('url');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('language');
            $table->json('topics');
            $table->unsignedInteger('watchers');
            $table->unsignedInteger('forks');
            $table->unsignedInteger('stars');
            $table->boolean('owner_is_organization');
            $table->string('owner_login');
            $table->string('owner_github_id');
            $table->string('avatar_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('frozen_repositories');
    }
};
