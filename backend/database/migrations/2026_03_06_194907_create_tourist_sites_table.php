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
        Schema::create('tourist_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_am')->nullable();
            $table->string('name_or')->nullable();
            $table->string('slug')->unique();

            $table->text('description_en');
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();

            $table->string('category')->nullable(); // e.g., Park, History, Culture, Nature
            $table->foreignId('woreda_id')->nullable()->constrained()->onDelete('set null');
            $table->string('location_name_en')->nullable(); // Specific location within woreda

            $table->string('cover_image_url')->nullable();
            $table->json('gallery_urls')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_sites');
    }
};
