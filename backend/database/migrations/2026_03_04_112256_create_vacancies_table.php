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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_am')->nullable();
            $table->string('title_or')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();
            $table->text('requirements_en')->nullable();
            $table->text('requirements_am')->nullable();
            $table->text('requirements_or')->nullable();
            $table->string('department')->nullable();
            $table->string('vacancy_type', 100)->nullable();
            $table->string('location_en')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
