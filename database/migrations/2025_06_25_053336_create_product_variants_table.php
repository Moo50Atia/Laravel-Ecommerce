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
        Schema::create('product_variants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->string('option_name'); // مثل: اللون / المقاس
        $table->string('option_value'); // مثل: أحمر / M
        $table->decimal('price_modifier', 10, 2)->nullable();
        $table->integer('stock')->default(0); // ✅ عدد النسخ المتاحة من هذا الاختيار
        $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};


