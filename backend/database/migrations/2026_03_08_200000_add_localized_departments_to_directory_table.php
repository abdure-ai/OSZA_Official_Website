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
            $table->string('department_am')->nullable()->after('department_en');
            $table->string('department_or')->nullable()->after('department_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->dropColumn(['department_am', 'department_or']);
        });
    }
};
