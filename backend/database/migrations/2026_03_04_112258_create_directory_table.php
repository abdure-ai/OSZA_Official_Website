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
        Schema::create('directory', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_am')->nullable();
            $table->string('name_or')->nullable();
            $table->string('position_en')->nullable();
            $table->string('position_am')->nullable();
            $table->string('position_or')->nullable();
            $table->string('department_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('office_location')->nullable();
            $table->string('photo_url', 1024)->nullable();
            $table->string('category', 100)->default('General');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directory');
    }
};
