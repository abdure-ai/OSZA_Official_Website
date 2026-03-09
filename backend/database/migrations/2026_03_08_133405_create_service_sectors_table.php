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
        Schema::create('service_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_am')->nullable();
            $table->string('name_or')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_or')->nullable();
            $table->text('icon_svg')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_sectors');
    }
};
