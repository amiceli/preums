<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('frozen_repositories', function (Blueprint $table) {
            $table->boolean('forRoad')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('frozen_repositories', function (Blueprint $table) {
            $table->dropColumn('forRoad');
        });
    }
};
