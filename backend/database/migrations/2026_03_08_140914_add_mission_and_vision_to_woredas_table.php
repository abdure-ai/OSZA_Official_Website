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
        Schema::table('woredas', function (Blueprint $table) {
            $table->text('mission_en')->nullable();
            $table->text('mission_am')->nullable();
            $table->text('mission_or')->nullable();
            $table->text('vision_en')->nullable();
            $table->text('vision_am')->nullable();
            $table->text('vision_or')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('woredas', function (Blueprint $table) {
            $table->dropColumn([
                'mission_en',
                'mission_am',
                'mission_or',
                'vision_en',
                'vision_am',
                'vision_or'
            ]);
        });
    }
};
