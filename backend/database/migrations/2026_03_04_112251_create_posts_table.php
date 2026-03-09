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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_or')->nullable();
            $table->text('content_en')->nullable();
            $table->text('content_am')->nullable();
            $table->text('content_or')->nullable();
            $table->enum('category', ['news', 'press_release', 'update']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('thumbnail_url', 1024)->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
