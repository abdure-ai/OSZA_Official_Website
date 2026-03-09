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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_or')->nullable();
            $table->string('file_url', 1024);
            $table->string('file_type', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('cover_image_url', 1024)->nullable();
            $table->string('author')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language', 50)->default('English');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
