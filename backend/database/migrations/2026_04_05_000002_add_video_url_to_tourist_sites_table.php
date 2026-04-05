<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->string('video_url', 512)->nullable()->after('cover_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
