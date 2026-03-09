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
        Schema::create('woreda_service_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_sector_id')->constrained()->onDelete('cascade');

            // Official Assignment Details (Pivot Data)
            $table->string('official_name_en')->nullable();
            $table->string('official_name_am')->nullable();
            $table->string('official_name_or')->nullable();
            $table->string('official_title_en')->nullable();
            $table->string('official_title_am')->nullable();
            $table->string('official_title_or')->nullable();
            $table->string('official_phone')->nullable();
            $table->string('official_email')->nullable();
            $table->string('official_photo_url')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woreda_service_sectors');
    }
};
