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

        Schema::create('predecessors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('child_id')
                ->constrained('pro_langs')
                ->cascadeOnDelete();
            $table->foreignId('parent_id')
                ->constrained('pro_langs')
                ->cascadeOnDelete();
            $table->unique(
                array('child_id', 'parent_id')
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('lang_author_pro_lang');
        Schema::dropIfExists('predecessors');
    }
};
