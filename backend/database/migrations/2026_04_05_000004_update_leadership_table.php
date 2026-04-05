<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leadership', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('rank_order');
            $table->integer('hierarchy_level')->default(1)->after('parent_id');
            $table->string('email')->nullable()->after('hierarchy_level');
            $table->string('phone')->nullable()->after('email');
            $table->string('office_location_en')->nullable()->after('phone');
            $table->string('office_location_am')->nullable()->after('office_location_en');
            $table->string('office_location_or')->nullable()->after('office_location_am');

            $table->foreign('parent_id')->references('id')->on('leadership')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leadership', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'hierarchy_level', 'email', 'phone', 'office_location_en', 'office_location_am', 'office_location_or']);
        });
    }
};
