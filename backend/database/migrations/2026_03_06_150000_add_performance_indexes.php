<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Adds performance indexes to frequently queried columns.
     */
    public function up(): void
    {
        // woredas: filtered by is_active, looked up by slug
        Schema::table('woredas', function (Blueprint $table) {
            $table->index('is_active', 'idx_woredas_is_active');
            $table->index('slug', 'idx_woredas_slug');
        });

        // posts: filtered by status & category, ordered by published_at
        Schema::table('posts', function (Blueprint $table) {
            $table->index('status', 'idx_posts_status');
            $table->index('category', 'idx_posts_category');
            $table->index('published_at', 'idx_posts_published_at');
            $table->index(['status', 'published_at'], 'idx_posts_status_published');
        });

        // gallery_items: filtered by is_active, category, woreda_id; ordered by sort_order
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->index('is_active', 'idx_gallery_is_active');
            $table->index('category', 'idx_gallery_category');
            $table->index('woreda_id', 'idx_gallery_woreda_id');
            $table->index('sort_order', 'idx_gallery_sort_order');
            $table->index(['category', 'sort_order'], 'idx_gallery_category_sort');
        });

        // emergency_alerts: filtered by is_active, expires_at
        Schema::table('emergency_alerts', function (Blueprint $table) {
            $table->index('is_active', 'idx_alerts_is_active');
            $table->index('expires_at', 'idx_alerts_expires_at');
            $table->index(['is_active', 'expires_at'], 'idx_alerts_active_expires');
        });

        // tenders: filtered by status, ordered by deadline
        Schema::table('tenders', function (Blueprint $table) {
            $table->index('status', 'idx_tenders_status');
            $table->index('deadline', 'idx_tenders_deadline');
        });

        // vacancies: filtered by is_active, ordered by deadline
        Schema::table('vacancies', function (Blueprint $table) {
            $table->index('is_active', 'idx_vacancies_is_active');
            $table->index('deadline', 'idx_vacancies_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('woredas', function (Blueprint $table) {
            $table->dropIndex('idx_woredas_is_active');
            $table->dropIndex('idx_woredas_slug');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_posts_status');
            $table->dropIndex('idx_posts_category');
            $table->dropIndex('idx_posts_published_at');
            $table->dropIndex('idx_posts_status_published');
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropIndex('idx_gallery_is_active');
            $table->dropIndex('idx_gallery_category');
            $table->dropIndex('idx_gallery_woreda_id');
            $table->dropIndex('idx_gallery_sort_order');
            $table->dropIndex('idx_gallery_category_sort');
        });

        Schema::table('emergency_alerts', function (Blueprint $table) {
            $table->dropIndex('idx_alerts_is_active');
            $table->dropIndex('idx_alerts_expires_at');
            $table->dropIndex('idx_alerts_active_expires');
        });

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropIndex('idx_tenders_status');
            $table->dropIndex('idx_tenders_deadline');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropIndex('idx_vacancies_is_active');
            $table->dropIndex('idx_vacancies_deadline');
        });
    }
};
