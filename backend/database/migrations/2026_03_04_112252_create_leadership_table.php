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
        Schema::create('leadership', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->nullable();
            $table->string('name_am')->nullable();
            $table->string('name_or')->nullable();
            $table->string('position_en')->nullable();
            $table->string('position_am')->nullable();
            $table->string('position_or')->nullable();
            $table->text('bio_en')->nullable();
            $table->text('bio_am')->nullable();
            $table->text('bio_or')->nullable();
            $table->string('photo_url', 1024)->nullable();
            $table->integer('rank_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leadership');
    }
};
