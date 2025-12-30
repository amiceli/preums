<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('year_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('apiId')->unique();
            $table->string('name')->unique();
            $table->integer('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('year_groups');
    }
};
