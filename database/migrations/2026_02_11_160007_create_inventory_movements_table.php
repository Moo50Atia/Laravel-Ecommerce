<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks every stock movement (in/out/adjustment) for inventory management.
 * Provides a complete audit trail of stock changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->enum('movement_type', ['in', 'out', 'adjustment']);
            $table->integer('quantity'); // positive for in, negative for out
            $table->string('reference_type')->nullable(); // e.g. App\Models\Order
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['product_id', 'created_at'], 'idx_inventory_product_date');
            $table->index('variant_id', 'idx_inventory_variant');

            $table->foreign('variant_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
