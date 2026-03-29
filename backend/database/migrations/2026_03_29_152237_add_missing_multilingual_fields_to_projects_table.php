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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'location_am')) {
                $table->string('location_am')->nullable()->after('location_en');
                $table->string('location_or')->nullable()->after('location_am');
            }
            if (!Schema::hasColumn('projects', 'contractor_am')) {
                $table->string('contractor_am')->nullable()->after('contractor');
                $table->string('contractor_or')->nullable()->after('contractor_am');
            }
            if (!Schema::hasColumn('projects', 'funding_source_am')) {
                $table->string('funding_source_am')->nullable()->after('funding_source');
                $table->string('funding_source_or')->nullable()->after('funding_source_am');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'location_am', 'location_or',
                'contractor_am', 'contractor_or',
                'funding_source_am', 'funding_source_or'
            ]);
        });
    }
};
