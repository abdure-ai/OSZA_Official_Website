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
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('title_am')->nullable()->after('title');
            $table->string('title_or')->nullable()->after('title_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['title_am', 'title_or']);
        });
    }
};
