<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Many to many lang can have many predecessors and can be predecessor of many langs
 */
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('lang_author_pro_lang', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('pro_lang_id')->unsigned();
            $table->integer('lang_author_id')->unsigned();

            $table
                ->foreign('pro_lang_id')
                ->references('id')
                ->on('pro_langs')
                ->onDelete('cascade');

            $table
                ->foreign('lang_author_id')
                ->references('id')
                ->on('lang_authors')
                ->onDelete('cascade');
        });

        Schema::create('famiglia', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('langId')->unsigned();
            $table->integer('authorId')->unsigned();

            $table
                ->foreign('langId')
                ->references('id')
                ->on('pro_langs')
                ->onDelete('cascade');

            $table
                ->foreign('authorId')
                ->references('id')
                ->on('lang_authors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('predecessors');
    }
};
