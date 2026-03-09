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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_or')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();
            $table->string('ref_number', 100)->nullable();
            $table->dateTime('deadline')->nullable();
            $table->string('file_url', 1024)->nullable();
            $table->enum('status', ['active', 'closed', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
