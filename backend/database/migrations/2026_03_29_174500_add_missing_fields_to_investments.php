<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->string('title_am')->nullable()->after('title_en');
            $table->string('title_or')->nullable()->after('title_am');
            $table->text('description_am')->nullable()->after('description_en');
            $table->text('description_or')->nullable()->after('description_am');
            $table->string('location_am')->nullable()->after('location');
            $table->string('location_or')->nullable()->after('location_am');
            $table->text('incentives_am')->nullable()->after('incentives_en');
            $table->text('incentives_or')->nullable()->after('incentives_am');
            $table->string('sector')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn([
                'title_am', 'title_or', 
                'description_am', 'description_or', 
                'location_am', 'location_or', 
                'incentives_am', 'incentives_or',
                'sector'
            ]);
        });
    }
};
