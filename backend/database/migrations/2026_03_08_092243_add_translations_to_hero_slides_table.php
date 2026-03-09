<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('title_am')->nullable();
            $table->string('subtitle_am')->nullable();
            $table->string('cta_text_am')->nullable();
            $table->string('title_or')->nullable();
            $table->string('subtitle_or')->nullable();
            $table->string('cta_text_or')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn([
                'title_am',
                'subtitle_am',
                'cta_text_am',
                'title_or',
                'subtitle_or',
                'cta_text_or'
            ]);
        });
    }
};
