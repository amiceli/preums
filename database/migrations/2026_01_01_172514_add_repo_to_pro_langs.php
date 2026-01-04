<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('pro_langs', function (Blueprint $table) {
            $table->string('mainRepository')->nullable();
            $table->string('codeTitle')->default('Sample code');
            $table->longText('rawCode')->nullable();
            $table->longText('rawCodeLink')->nullable();
            $table->boolean('isHidden')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('pro_langs', function (Blueprint $table) {
            $table->dropColumn('mainRepository');
            $table->dropColumn('codeTitle');
            $table->dropColumn('rawCode');
            $table->dropColumn('isHidden');
        });
    }
};
