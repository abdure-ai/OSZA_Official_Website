<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('page', 512)->nullable();
            $table->string('referrer', 512)->nullable();
            $table->enum('device', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->string('browser', 100)->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('locale', 10)->nullable();
            $table->timestamp('visited_at')->useCurrent()->index();
            $table->timestamps();

            $table->index(['visited_at', 'device']);
            $table->index('ip_address');
            $table->index('page');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
