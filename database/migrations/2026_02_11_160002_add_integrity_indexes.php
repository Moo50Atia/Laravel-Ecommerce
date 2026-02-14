<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds data integrity indexes: unique constraints to prevent duplicates
 * and composite indexes for polymorphic lookups.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── Wishlists: Prevent duplicate wishlist entries ──────
        Schema::table('wishlists', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id'], 'uq_wishlists_user_product');
        });

        // ─── Product Reviews: Prevent duplicate reviews ────────
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id'], 'uq_product_reviews_user_product');
            $table->index('product_id', 'idx_product_reviews_product_id');
            $table->index('is_approved', 'idx_product_reviews_is_approved');
        });

        // ─── Images: Polymorphic composite index ───────────────
        Schema::table('images', function (Blueprint $table) {
            $table->index(['imageable_type', 'imageable_id'], 'idx_images_imageable');
        });

        // ─── Blog Reviews ──────────────────────────────────────
        Schema::table('blog_reviews', function (Blueprint $table) {
            $table->index('blog_id', 'idx_blog_reviews_blog_id');
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropUnique('uq_wishlists_user_product');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropUnique('uq_product_reviews_user_product');
            $table->dropIndex('idx_product_reviews_product_id');
            $table->dropIndex('idx_product_reviews_is_approved');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex('idx_images_imageable');
        });

        Schema::table('blog_reviews', function (Blueprint $table) {
            $table->dropIndex('idx_blog_reviews_blog_id');
        });
    }
};
