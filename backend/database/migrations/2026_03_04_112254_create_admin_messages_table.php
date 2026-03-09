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
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Zone Administrator');
            $table->string('title_position')->default('Chief Administrator, Oromo Special Zone');
            $table->text('message_en');
            $table->text('message_am')->nullable();
            $table->text('message_or')->nullable();
            $table->string('photo_url', 1024)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_messages');
    }
};
