<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds full-text search indexes to replace LIKE '%keyword%' queries.
 * Using MATCH...AGAINST is ~100x faster than LIKE at 1M+ rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->fullText(['name', 'description'], 'ft_products_name_description');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->fullText(['title', 'content'], 'ft_blogs_title_content');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->fullText(['name', 'email'], 'ft_users_name_email');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->fullText(['code'], 'ft_coupons_code');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropFullText('ft_products_name_description');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropFullText('ft_blogs_title_content');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropFullText('ft_users_name_email');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropFullText('ft_coupons_code');
        });
    }
};
