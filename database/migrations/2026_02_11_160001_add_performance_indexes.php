<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds performance-critical B-tree indexes to tables that support
 * admin filtering, reporting, and pagination queries.
 * Designed for 1M+ records per table.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── Orders ────────────────────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
            $table->index('payment_status', 'idx_orders_payment_status');
            $table->index('payment_method', 'idx_orders_payment_method');
            $table->index('created_at', 'idx_orders_created_at');
            // $table->index('vendor_id', 'idx_orders_vendor_id');
            $table->index(['status', 'created_at'], 'idx_orders_status_created');
        });

        // ─── Products ──────────────────────────────────────────
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_active', 'idx_products_is_active');
            $table->index('is_featured', 'idx_products_is_featured');
            $table->index('price', 'idx_products_price');
            $table->index(['vendor_id', 'is_active'], 'idx_products_vendor_active');
        });

        // ─── Categories ────────────────────────────────────────
        Schema::table('categories', function (Blueprint $table) {
            $table->index('parent_id', 'idx_categories_parent_id');
            $table->index('is_active', 'idx_categories_is_active');
        });

        // ─── Users ─────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('status', 'idx_users_status');
        });

        // ─── User Addresses ────────────────────────────────────
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->index('city', 'idx_user_addresses_city');
        });

        // ─── Coupons ───────────────────────────────────────────
        Schema::table('coupons', function (Blueprint $table) {
            $table->index('is_active', 'idx_coupons_is_active');
            $table->index(['valid_from', 'valid_to'], 'idx_coupons_validity_range');
        });

        // ─── Subscriptions ─────────────────────────────────────
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index('status', 'idx_subscriptions_status');
            $table->index('user_id', 'idx_subscriptions_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_payment_status');
            $table->dropIndex('idx_orders_payment_method');
            $table->dropIndex('idx_orders_created_at');
            // $table->dropIndex('idx_orders_vendor_id');
            $table->dropIndex('idx_orders_status_created');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_is_active');
            $table->dropIndex('idx_products_is_featured');
            $table->dropIndex('idx_products_price');
            $table->dropIndex('idx_products_vendor_active');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_parent_id');
            $table->dropIndex('idx_categories_is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_status');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropIndex('idx_user_addresses_city');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex('idx_coupons_is_active');
            $table->dropIndex('idx_coupons_validity_range');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_subscriptions_status');
            $table->dropIndex('idx_subscriptions_user_id');
        });
    }
};
