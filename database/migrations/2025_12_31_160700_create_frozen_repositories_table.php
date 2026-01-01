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
            $table->string('githubId');
            $table->string('name');
            $table->string('fullName');
            $table->longText('description')->nullable();
            $table->string('url');
            $table->dateTime('githubCreatedAt');
            $table->dateTime('githubUpdatedAt');
            $table->string('language')->nullable();
            $table->json('topics');
            $table->unsignedInteger('watchers');
            $table->unsignedInteger('forks');
            $table->unsignedInteger('stars');
            $table->boolean('ownerIsOrganization');
            $table->string('ownerLogin');
            $table->string('ownerGithubId');
            $table->string('ownerAvatarUrl');
            $table->string('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('frozen_repositories');
    }
};
