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
        Schema::table('office_settings', function (Blueprint $table) {
            $table->string('address_am')->nullable()->after('address');
            $table->string('address_or')->nullable()->after('address_am');
            $table->string('working_hours_am')->nullable()->after('working_hours');
            $table->string('working_hours_or')->nullable()->after('working_hours_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office_settings', function (Blueprint $table) {
            $table->dropColumn(['address_am', 'address_or', 'working_hours_am', 'working_hours_or']);
        });
    }
};
