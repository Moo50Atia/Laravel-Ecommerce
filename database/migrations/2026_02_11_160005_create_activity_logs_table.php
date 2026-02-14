<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the activity_logs table for general audit trail.
 * Tracks all CRUD operations across models using polymorphic relationships.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_type', 100);
            $table->string('event', 100); // e.g. order.created, product.price_changed
            $table->string('trackable_type');
            $table->unsignedBigInteger('trackable_id');
            $table->string('causer_type')->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->json('properties')->nullable(); // old/new values
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Indexes for efficient querying
            $table->index(['trackable_type', 'trackable_id'], 'idx_activity_trackable');
            $table->index('causer_id', 'idx_activity_causer');
            $table->index('created_at', 'idx_activity_created_at');
            $table->index(['log_type', 'event'], 'idx_activity_type_event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
