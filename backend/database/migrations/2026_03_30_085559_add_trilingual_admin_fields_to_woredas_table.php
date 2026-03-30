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
        Schema::table('woredas', function (Blueprint $table) {
            $table->string('administrator_name_am')->nullable()->after('administrator_name');
            $table->string('administrator_name_or')->nullable()->after('administrator_name_am');
            $table->string('administrator_title_am')->nullable()->after('administrator_title');
            $table->string('administrator_title_or')->nullable()->after('administrator_title_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('woredas', function (Blueprint $table) {
            $table->dropColumn([
                'administrator_name_am',
                'administrator_name_or',
                'administrator_title_am',
                'administrator_title_or'
            ]);
        });
    }
};
