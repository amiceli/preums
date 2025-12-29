<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('pro_langs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('apiId');
            $table->string('link')->nullable();
            $table->string('pictureUrl')->nullable();
            $table->string('name');
            $table->string('company')->nullable();
            $table->jsonb('years');
            $table->integer('yearGroupId');

            $table->foreign('yearGroupId')
                ->references('id')
                ->on('year_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('pro_langs');
    }
};
