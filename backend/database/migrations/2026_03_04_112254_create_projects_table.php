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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_or')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();
            $table->string('location_en')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Planning', 'Ongoing', 'Completed', 'On Hold'])->default('Planning');
            $table->decimal('budget', 15, 2)->nullable();
            $table->integer('progress')->default(0);
            $table->string('contractor')->nullable();
            $table->string('funding_source')->nullable();
            $table->boolean('is_published')->default(true);
            $table->string('cover_image_url', 1024)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
