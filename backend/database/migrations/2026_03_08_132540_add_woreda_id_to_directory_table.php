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
        Schema::table('directory', function (Blueprint $table) {
            $table->foreignId('woreda_id')->nullable()->after('id')->constrained('woredas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->dropForeign(['woreda_id']);
            $table->dropColumn('woreda_id');
        });
    }
};
