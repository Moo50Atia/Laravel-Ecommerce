<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamp("expires_at")->nullable();
        $table->string('order_number')->unique();
        $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'canceled', 'refunded'])->default('pending');
        $table->decimal('total_amount', 12, 2)->nullable();
        $table->decimal('discount_amount', 12, 2)->default(0.00)->nullable();
        $table->decimal('shipping_amount', 8, 2)->nullable();
        $table->decimal('grand_total', 12, 2)->nullable();
        $table->enum('payment_method', ['credit_card', 'cod', 'bank_transfer'])->nullable();
        $table->enum('payment_status', ['paid', 'unpaid', 'failed'])->default('unpaid')->nullable();
        $table->json('shipping_address')->nullable();
        $table->json('billing_address')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


