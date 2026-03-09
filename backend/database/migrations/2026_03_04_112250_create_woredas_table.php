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
        Schema::create('woredas', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_am')->nullable();
            $table->string('name_or')->nullable();
            $table->string('slug')->unique();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();
            $table->string('population')->nullable();
            $table->string('area_km2')->nullable();
            $table->string('established_year')->nullable();
            $table->string('capital_en')->nullable();
            $table->string('capital_am')->nullable();
            $table->string('capital_or')->nullable();
            $table->string('administrator_name')->nullable();
            $table->string('administrator_title')->default('Woreda Administrator');
            $table->string('administrator_photo_url', 1024)->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('address_en')->nullable();
            $table->string('address_am')->nullable();
            $table->string('address_or')->nullable();
            $table->string('banner_url', 1024)->nullable();
            $table->string('logo_url', 1024)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woredas');
    }
};
