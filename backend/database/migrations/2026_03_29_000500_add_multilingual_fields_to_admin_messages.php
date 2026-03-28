<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->string('name_am')->nullable()->after('name');
            $table->string('name_or')->nullable()->after('name_am');
            $table->string('title_position_am')->nullable()->after('title_position');
            $table->string('title_position_or')->nullable()->after('title_position_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->dropColumn(['name_am', 'name_or', 'title_position_am', 'title_position_or']);
        });
    }
};
